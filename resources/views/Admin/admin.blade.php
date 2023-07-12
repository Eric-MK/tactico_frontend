<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin-Page</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .main-body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="main-body">
    @include('Admin.adminNav')

    <div class="container">
        <h1>Verified Accounts</h1>
          <!-- Display success message -->
          @if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif

      <!-- Display error message -->
      @if(session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                    <!-- Add additional columns if needed -->
                </tr>
            </thead>
            <tbody>
                @foreach ($verifiedUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            <form action="{{ route('admin.unverify', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Unverify</button>
                            </form>
                        </td>
                        <!-- Add additional columns if needed -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
