from flask import Flask, request, jsonify
import logging

app = Flask(__name__)

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s - %(message)s",
    handlers=[
        logging.StreamHandler(),  
        logging.FileHandler("network_simulator.log")  
    ]
)

@app.route('/simulate', methods=['POST'])
def simulate_network():
    try: 
        transaction_data = request.json
        transaction_id = transaction_data.get("transaction_id", "Unknown")
        
        logging.info(f"Transaction {transaction_id} received from Transaction Switch")
        
        response = {
            "status": "approved",
            "transaction_id": transaction_id,
            "message": "Transaction authorised successfully."
        }
 
        logging.info(f"Transaction {transaction_id} approved")
        
        return jsonify(response)
    except Exception as e:
        logging.error(f"Error in network simulation: {e}")
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(port=5001, threaded=True)
