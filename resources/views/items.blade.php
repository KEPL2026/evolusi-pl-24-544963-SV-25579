<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Web Evolusi PL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f5f5f5; 
            margin: 0; 
            padding: 40px 20px; 
            color: #333;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
        }
        .header-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.8; }
        .btn-add { background-color: #bce2c2; }
        .btn-edit { background-color: #fbcc9f; margin-right: 8px; }
        .btn-delete { background-color: #f59c9d; }

        .table-wrapper {
            background-color: #fcfcfc;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background-color: #e8ece6;
            color: #5a8661;
            padding: 16px 20px;
            font-size: 16px;
            font-weight: 700;
        }
        td {
            padding: 16px 20px;
            border-top: 1px solid #eaeaea;
            font-size: 15px;
            color: #555;
            vertical-align: middle;
        }
        tr:hover {
            background-color: #f9faf8;
        }
        .actions-cell {
            white-space: nowrap;
            width: 1%;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            display: none;
            align-items: center; justify-content: center;
            z-index: 1000;
        }
        .modal {
            background: white; padding: 24px;
            border-radius: 8px; width: 100%; max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .modal h2 { margin-top: 0; color: #5a8661; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #555; }
        .form-group input, .form-group textarea {
            width: 100%; padding: 10px;
            border: 1px solid #ccc; border-radius: 4px;
            font-family: inherit;
        }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn-cancel { background: #bbb; color: white; }

        .alert {
            background: #d4edda; color: #155724; padding: 12px 20px;
            border-radius: 4px; margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>

<div class="container">
    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="header-actions">
        <button class="btn btn-add" onclick="openAddModal()">ADD</button>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description ?? 'N/A' }}</td>
                        <td class="actions-cell">
                            <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline-block; margin:0;">
                                <button type="button" class="btn btn-edit" onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->description) }}')">EDIT</button>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this item?')">DELETE</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999;">No items found. Click ADD to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h2>Add Item</h2>
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('addModal')">CANCEL</button>
                <button type="submit" class="btn btn-add">SAVE</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h2>Edit Item</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="editName" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="editDesc" rows="3"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('editModal')">CANCEL</button>
                <button type="submit" class="btn btn-edit" style="color:white;">UPDATE</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }
    
    function openEditModal(id, name, desc) {
        document.getElementById('editForm').action = '/items/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editDesc').value = desc;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.className === 'modal-overlay') {
            event.target.style.display = 'none';
        }
    }
</script>

</body>
</html>
