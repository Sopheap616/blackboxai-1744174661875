<div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden" id="transactionModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-semibold">Add Transaction</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="transactionForm" class="p-6">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Transaction Type</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="income" checked 
                            class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2">Income</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="expense" 
                            class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2">Expense</span>
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Category</label>
                <select name="category" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    <optgroup label="Income">
                        <option value="1">Salary</option>
                        <option value="2">Business</option>
                        <option value="3">Investment</option>
                    </optgroup>
                    <optgroup label="Expense">
                        <option value="4">Food</option>
                        <option value="5">Transport</option>
                        <option value="6">Utilities</option>
                        <option value="7">Entertainment</option>
                    </optgroup>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Amount</label>
                <div class="flex">
                    <input type="number" name="amount" step="0.01" min="0" required
                        class="w-full px-3 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <select name="currency" required
                        class="px-3 py-2 border-t border-b border-r rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">USD ($)</option>
                        <option value="2">KHR (៛)</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Date</label>
                <input type="date" name="date" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="<?= date('Y-m-d') ?>">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Save Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('transactionModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Handle form submission
document.getElementById('transactionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Form submission logic will be added later
    closeModal();
});
</script>
