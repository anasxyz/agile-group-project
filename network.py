from flask import Flask, request, jsonify
import datetime

app = Flask(__name__)

def log(message, level="INFO"):
    timestamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    log_message = f"{timestamp} - {level} - {message}"
    
    print(log_message)  # log in terminal

    # log in file
    with open('network.log', 'a') as f:
        f.write(log_message + "\n")

@app.route('/simulate', methods=['POST'])
def simulate_network():
    try:
        transaction_data = request.json
        transaction_id = transaction_data.get("transaction_id", "Unknown")
        
        log(f"Transaction {transaction_id} received from Transaction Switch")
        
        response = {
            "status": "approved",
            "transaction_id": transaction_id,
            "message": "Transaction authorised successfully."
        }
        
        log(f"Transaction {transaction_id} approved")
        
        return jsonify(response)
    except Exception as e:
        log(f"Error in network simulation: {e}", level="ERROR")
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(port=5001, threaded=True)
