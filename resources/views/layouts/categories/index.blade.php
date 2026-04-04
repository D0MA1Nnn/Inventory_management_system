<div class="p-6">

    <h2 class="text-lg font-bold mb-4">Categories</h2>

    <!-- ADD CATEGORY FORM -->
    <form id="categoryForm" class="flex gap-2 mb-4">
        <input type="text" id="name" placeholder="Category Name"
                class="border p-2 flex-1 rounded">

        <button class="bg-blue-500 text-white px-4 rounded">
            Add
        </button>
    </form>

    <!-- CATEGORY TABLE -->
    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 text-left">Name</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody id="categoryTable"></tbody>
    </table>

</div>



<script>
async function loadCategoriesTable() {
    let res = await fetch('/api/categories');
    let data = await res.json();

    let table = document.getElementById('categoryTable');
    table.innerHTML = '';

    data.forEach(c => {
        table.innerHTML += `
            <tr class="border-t">
                <td class="p-2">${c.name}</td>
                <td class="p-2">
                    <button onclick="deleteCategory(${c.id})"
                        class="text-red-500 text-sm">
                        Delete
                    </button>
                </td>
            </tr>
        `;
    });
}

document.getElementById('categoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    let name = document.getElementById('name').value;

    await fetch('/api/categories', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name })
    });

    this.reset();
    loadCategoriesTable();
});

async function deleteCategory(id) {
    await fetch('/api/categories/' + id, {
        method: 'DELETE'
    });

    loadCategoriesTable();
}

// Load on page open
loadCategoriesTable();
</script>