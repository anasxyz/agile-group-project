from flask import Flask, render_template
from flask_socketio import SocketIO, emit
import json

app = Flask(__name__)
socketio = SocketIO(app)

@app.route('/simulate')
def index():
    return render_template('index.html')

@socketio.on('connect')
def handle_connect():
    print('test connected')
    emit('message', json.dumps({'text': 'Ready to receive messages!'}))

@socketio.on('message')
def handle_message(data):
    print(f"test received message: {data}")
    # Assuming the message is JSON, parse it
    message = json.loads(data)
    print(f"Displaying: {message['text']}")

    # Send back a response if needed
    emit('message', json.dumps({'text': f"Test got: {message['text']}"}))



if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5001)
