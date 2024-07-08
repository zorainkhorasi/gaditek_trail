<!DOCTYPE html>
<html>
<head>
    <title>Registeration form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-3 mb-3">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <h3>Registration Form</h3>
    <form id="registration-form" action="{{ route('user.register.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" id="username">
            <p id="username-error"></p>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="email">
            <p id="email-error"></p>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" id="password">
            <p id="password-error"></p>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<script>
    const form = document.getElementById('registration-form');

    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        validateForm();
    });

    function validateForm() {
        let isValid = true;



        if (usernameInput.value.trim() == '') {

            document.getElementById('username-error').textContent = 'Username is required';

            isValid = false;
        } else {
            document.getElementById('username-error').textContent = '';
        }


        if (emailInput.value.trim() === '') {
            document.getElementById('email-error').textContent = 'Email is required';
            isValid = false;
        } else if (!validateEmail(emailInput.value)) {
            document.getElementById('email-error').textContent = 'Invalid email address';
            isValid = false;
        } else {
            document.getElementById('email-error').textContent = '';
        }


        if (passwordInput.value.trim() === '') {
            document.getElementById('password-error').textContent = 'Password is required';
            isValid = false;
        } else {
            document.getElementById('password-error').textContent = '';
        }

        if (isValid) {
            form.submit();
        }
    }

    function validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }
</script>
</body>
</html>
