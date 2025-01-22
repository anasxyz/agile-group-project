from flask import Flask, render_template
from flask_socketio import SocketIO, emit
import json
import mysql.connector
from decimal import Decimal

app = Flask(__name__)
socketio = SocketIO(app)

# Database configuration
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "accounts",
    "port": 3306
}

def get_db_connection():
    try:
        conn = mysql.connector.connect(**db_config)
        return conn
    except mysql.connector.Error as err:
        print(f"Database connection error: {err}")
        return None

@app.route('/simulate')
def index():
    return render_template('index.html')

@socketio.on('connect')
def handle_connect():
    print('Simulator connected')
    emit('message', json.dumps({'text': 'Simulator is ready!'}))

@socketio.on('message')
def handle_message(data):
    try:
        # Parse the incoming JSON message
        transaction_data = json.loads(data)
        conn = get_db_connection()
        if not conn:
            emit('message', json.dumps({"status": "error", "message": "Database connection failed"}))
            return

        # Query the database to validate the transaction
        cursor = conn.cursor(dictionary=True)
        query = """
            SELECT * FROM account_info
            WHERE card_number = %s AND expiry_date = %s AND atm_id = %s
              AND unique_transaction_id = %s AND pin = %s
        """
        cursor.execute(query, (
            transaction_data['card_number'],
            transaction_data['expiry_date'],
            transaction_data['atm_id'],
            transaction_data['unique_transaction_id'],
            transaction_data['pin']
        ))

        result = cursor.fetchone()
        if result:
            # Check if withdrawal amount is less than or equal to the balance
            if Decimal(transaction_data['withdrawal_amount']) <= result['balance']:
                # Authorize the transaction and update the balance
                new_balance = result['balance'] - Decimal(transaction_data['withdrawal_amount'])
                update_query = """
                    UPDATE account_info
                    SET balance = %s
                    WHERE unique_transaction_id = %s
                """
                cursor.execute(update_query, (new_balance, result['unique_transaction_id']))
                conn.commit()

                # Respond with success
                response = {
                    "TransactionID": transaction_data['unique_transaction_id'],
                    "Status": "Authorized"
                }
                print("Transaction authorized:", response)
                emit('message', json.dumps(response))
            else:
                # Respond with insufficient funds
                response = {
                    "TransactionID": transaction_data['unique_transaction_id'],
                    "Status": "Insufficient Funds"
                }
                print("Transaction denied:", response)
                emit('message', json.dumps(response))
        else:
            # Respond with invalid transaction details
            response = {
                "TransactionID": transaction_data.get('unique_transaction_id', 'Unknown'),
                "Status": "Invalid Transaction Details"
            }
            print("Transaction denied:", response)
            emit('message', json.dumps(response))

        cursor.close()
        conn.close()
    except Exception as e:
        print("Error processing message:", e)
        emit('message', json.dumps({"status": "error", "message": str(e)}))

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5001)
