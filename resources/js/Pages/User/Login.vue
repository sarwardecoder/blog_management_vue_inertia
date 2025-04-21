<script setup>
</script>

<template>
    <div class="container">
    <br>  @if(session('fail'))
            <div class="alert alert-danger">
                {{ session('success') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <h2>Login</h2>

        <form method="POST" action="{{ route('user.check') }}">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</template>

<style scoped>
</style>