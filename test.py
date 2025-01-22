import json
import socketio
from flask import Flask, render_template
from flask_socketio import SocketIO

sio_client = socketio.Client()

def connect_to_simulator():
    sio_client.connect('http://localhost:5001')  # Connect to simulator server
    print("Connected to simulator")

# Send JSON data to simulator
def send_transaction_to_simulator():
    transaction_data = {
        "card_number": "1234567890123456",
        "expiry_date": "2025-12-31",
        "atm_id": "ATM001",
        "unique_transaction_id": "TXN001",
        "pin": "1234",
        "withdrawal_amount": 1.00
    }

    sio_client.emit('message', json.dumps(transaction_data))
    print("Sent transaction data to simulator:", transaction_data)

app = Flask(__name__)
socketio = SocketIO(app)

@app.route('/')
def index():
    return render_template('index.html')

@socketio.on('connect')
def handle_connect():
    print('Client connected')
    send_transaction_to_simulator()  # Send transaction data on connect

if __name__ == '__main__':
    connect_to_simulator()
    app.run(debug=True, host='0.0.0.0', port=5002)
