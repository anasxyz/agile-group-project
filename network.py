from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/approve', methods=['POST'])
def simulate_network():
    try:
        transaction_data = request.json
                
        response = {
            "status": "approved",
            "transaction_id": transaction_data.get("transaction_id"),
            "message": "Transaction authorised successfully."
        }
        return jsonify(response)
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(port=5001)
