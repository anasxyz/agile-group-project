import json
import socketio  
from flask import Flask, render_template
from flask_socketio import SocketIO, emit

sio_client = socketio.Client()

def connect_to_simulator():
    sio_client.connect('http://localhost:5001')  
    print("Connected to simulator")

# Send a message from test to simulator
def send_message_to_simulator(message):
    sio_client.emit('message', json.dumps({'text': message}))

app = Flask(__name__)
socketio = SocketIO(app)

@app.route('/')
def index():
    return render_template('index.html')

@socketio.on('connect')
def handle_connect():
    print('Client 1 connected')
    emit('message', json.dumps({'text': 'Hello from test!'}))  # Sending JSON to simulator
    send_message_to_simulator('Hello from test')  # Send message to simulator

@socketio.on('message')
def handle_message(data):
    print('Received message:', data)
    # Send the data to simulator
    send_message_to_simulator(data)

if __name__ == '__main__':
    connect_to_simulator()
    app.run(debug=True, host='0.0.0.0', port=5002)
