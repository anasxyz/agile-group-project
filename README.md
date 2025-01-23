Key Features
Network Simulator Integration:

The script forwards transactions to the Network Simulator using the $networkSimulatorUrl.

It uses cURL to send a POST request with the transaction data.

Local Simulation Mode:

If $useNetworkSimulator is set to false, the script simulates a response locally without contacting the Network Simulator.

This is useful for testing the Transaction Switch in isolation.

Logging:

All incoming transactions and responses (whether from the Network Simulator or simulated locally) are logged to transaction_switch.log.

Error Handling:

The script checks for valid POST requests and returns a 405 Method Not Allowed error for invalid methods.

How to Use
Set the Network Simulator URL:

Update the $networkSimulatorUrl variable with the actual URL of your Network Simulator.

Enable/Disable Network Simulator:

Set $useNetworkSimulator to true to use the Network Simulator.

Set it to false to simulate responses locally.
