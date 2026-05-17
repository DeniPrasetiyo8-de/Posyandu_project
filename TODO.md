# TODO: Lupa Password Feature Implementation

## Step 1: Add Password Reset Routes
- Add routes/web.php: forgot-password GET/POST routes

## Step 2: Update AuthController
- Add showForgotPasswordForm() method
- Add processForgotPassword() method to verify user identity
- Add showResetPasswordForm() method  
- Add processResetPassword() method to update password

## Step 3: Create Forgot Password View
- Create resources/views/pages/forgot-password.blade.php
- Form with: Nama Lengkap, No Telepon, RW fields

## Step 4: Update Login Page
- Add "Lupa Password?" link below login form

## Step 5: Test
- Verify the feature works correctly
