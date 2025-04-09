document.addEventListener('DOMContentLoaded', function() {
    // Initialize any necessary event listeners
    const addTransactionBtn = document.querySelector('#addTransactionBtn');
    if (addTransactionBtn) {
        addTransactionBtn.addEventListener('click', openModal);
    }

    // Currency conversion logic
    const currencySelects = document.querySelectorAll('[name="currency"]');
    currencySelects.forEach(select => {
        select.addEventListener('change', function() {
            // Handle currency change logic
        });
    });
});

// Global modal functions
function openModal() {
    document.getElementById('transactionModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Form validation
function validateTransactionForm(form) {
    // Add validation logic here
    return true;
}

// AJAX form submission
function submitTransactionForm(form) {
    if (!validateTransactionForm(form)) return false;
    
    const formData = new FormData(form);
    const url = form.dataset.transactionId ? 
        `transactions/update.php?id=${form.dataset.transactionId}` : 
        'transactions/save.php';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error saving transaction');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the transaction');
    });
}

// Edit transaction
function editTransaction(id) {
    fetch(`transactions/edit.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const transaction = data.data;
                const form = document.getElementById('transactionForm');
                
                // Set form values
                form.querySelector(`input[name="type"][value="${transaction.category_type}"]`).checked = true;
                form.querySelector('select[name="category"]').value = transaction.category_id;
                form.querySelector('input[name="amount"]').value = Math.abs(transaction.amount);
                form.querySelector('select[name="currency"]').value = transaction.currency_id;
                form.querySelector('input[name="date"]').value = transaction.transaction_date;
                form.querySelector('textarea[name="description"]').value = transaction.description || '';
                
                // Set form mode
                form.dataset.transactionId = transaction.id;
                document.querySelector('#transactionModal h3').textContent = 'Edit Transaction';
                
                openModal();
            } else {
                alert(data.message || 'Error loading transaction');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading the transaction');
        });
}

// Delete transaction
function deleteTransaction(id) {
    if (!confirm('Are you sure you want to delete this transaction?')) return;
    
    const formData = new FormData();
    formData.append('id', id);
    
    fetch('transactions/delete.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error deleting transaction');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the transaction');
    });
}

// Initialize event listeners for edit/delete buttons
function initTransactionButtons() {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            editTransaction(this.dataset.transactionId);
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteTransaction(this.dataset.transactionId);
        });
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initTransactionButtons();
    
    const addTransactionBtn = document.querySelector('#addTransactionBtn');
    if (addTransactionBtn) {
        addTransactionBtn.addEventListener('click', function() {
            const form = document.getElementById('transactionForm');
            form.reset();
            delete form.dataset.transactionId;
            document.querySelector('#transactionModal h3').textContent = 'Add Transaction';
            openModal();
        });
    }

    // Currency conversion logic
    const currencySelects = document.querySelectorAll('[name="currency"]');
    currencySelects.forEach(select => {
        select.addEventListener('change', function() {
            // Handle currency change logic
        });
    });
});
