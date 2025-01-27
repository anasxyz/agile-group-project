function showModal(title, message, button1Text, button2Text, button1Action, button2Action) {
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalMessage').textContent = message;
  document.getElementById('button1').textContent = button1Text;
  document.getElementById('button2').textContent = button2Text;

  document.getElementById('button1').setAttribute('onclick', button1Action);
  document.getElementById('button2').setAttribute('onclick', button2Action);

  document.getElementById('customModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('customModal').style.display = 'none';
}

function sendTransactionData(transaction_type) {
  const transaction_data = {
    card_number: '1234123412341234',
    expiry_date: '12/25',
    atm_id: 'ATM001',
    transaction_id: 'txn_' + Math.random().toString(36).substr(2, 9),
    pin: '1234',
    transaction_type: transaction_type
  };

  fetch('http://localhost/transaction_switch.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams(transaction_data).toString()
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    if (data.status === 'Approved') {
      showModal('Transaction Successful', data.status, 'Close', '', 'closeModal()', '');
    } else {
      showModal('Transaction Failed', data.status, 'Close', '', 'closeModal()', '');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showModal('Error', 'There was an error processing your request.', 'Close', '', 'closeModal()', '');
  });
}

function redirectToBalanceSummary(accountType) {
    const url = `balance_summary.php?account_type=${encodeURIComponent(accountType)}`;
    window.location.href = url;
}

function redirectToBalanceChoice(accountType) {
    const url = `balance_choice.php?account_type=${encodeURIComponent(accountType)}`;
    window.location.href = url;
}

function redirectToWithdrawalChoice(accountType) {
  const url = `withdrawal_choice.php?account_type=${encodeURIComponent(accountType)}`;
  window.location.href = url;
}

function redirect() {
  document.getElementById("redirectForm").submit();
}

function redirectWithdrawal() {
  window.location.href = "withdrawal_options.php";
}

function perform_another_transaction() {
  showModal("Perform Another Transaction?", "Would you like to do another transaction?", "NO", "YES", "redirectCardOut()", "redirectInsertCard()")
}

function redirectCardOut() {
  window.location.href = 'take_card_out.php';
}

function redirectInsertCard() {
  window.location.href = 'insert_card.php';
}

function transaction_cancelled() {
  showModal("Transaction Cancelled", 'Your transaction has been cancelled', "Okay", "", "redirectCardOut()", "");
}

function redirectIndex() {
  window.location.href = 'index.php';
}

function redirectToCustomAmountPage() {
  window.location.href = 'custom_amount.php';
}

