<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notifications</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 12px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .notification-card.unread {
            background-color: #1886e0;
        }
        .notification-card.read {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <a href="{{ route('dashboard.index') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="{{ route('products.index') }}"><i class="fa fa-box"></i> Products</a>
        <a href="{{ route('category.index') }}"><i class="fa fa-list"></i> Categories</a>
        <a href="{{ route('order.index') }}"><i class="fa fa-shopping-cart"></i> Orders</a>
        <a href="{{ route('customers.index') }}"><i class="fa fa-users"></i> Customers</a>
        <a href="{{ route('notification.index') }}" class="bg-primary p-2"><i class="fa fa-bell"></i> Notifications</a>
    </div>

    <div class="content">
        <div class="container mt-4">
            <h2>Notifications</h2>
            <form action="{{ route('notification.readBulk') }}" method="POST">
                @csrf
                <table id="notificationsTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}">
                            <td>
                                <input type="checkbox" name="notifications[]" value="{{ $notification->id }}" 
                                    {{ $notification->is_read ? 'disabled' : '' }}>
                            </td>
                            <td>
                                @if($notification->is_read)
                                    <span class="badge bg-success">Read</span>
                                @else
                                    <span class="badge bg-primary">Unread</span>
                                @endif
                            </td>
                            <td>{{ $notification->message }}</td>
                            <td>{{ $notification->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-sm btn-info">Mark Selected as Read</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#notificationsTable').DataTable({
                "order": [[2, "desc"]]
            });

            $('#selectAll').on('click', function() {
                var isChecked = this.checked;
                $('input[name="notifications[]"]').each(function() {
                    if (!$(this).prop('disabled')) {
                        $(this).prop('checked', isChecked);
                    }
                });
            });
        });
    </script>
</body>
</html>
