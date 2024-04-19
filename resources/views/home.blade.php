<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A Laravel Email Templates Package">
    <meta name="author" content="Imani Manyara, Simtabi">
    <meta name="generator" content="Simtabi">

    <title>Barua &mdash; A Laravel Email Templates Package</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/starter-template/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://unpkg.com/@webpixels/css@1.2.6/dist/index.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div id="app">

    <!-- Header -->

    <!-- Main container -->
    <div id="main-container" role="document">
        <main role="main">


            <div class="container-xxl">
                <div class="col-md-9 px-3 mx-auto">
                    <div class="text-center mt-20 mb-12">
                        <h1 class="display-3 ls-tight">
                            <span class="d-inline-flex text-transparent bg-clip-text gradient-bottom-right start-purple-500 end-indigo-400 position-relative">Barua</span>
                            <br>
                            Email Template Kit
                        </h1>
                        <p class="text-lg font-semibold mt-5 px-lg-32">
                            Effortlessly design and send responsive emails with customizable components.
                        </p>
                        <div class="mx-sm-n2 mt-7">
                            <a href="https://github.com/laranail/barua" class="btn btn-dark" target="_blank">
                                <i class="bi bi-github me-2"></i>
                                <span>See on Github</span>
                            </a>
                        </div>
                    </div>
                    <div class="row g-6 g-lg-6">

                        <div class="col-12 col-md-6">
                            <div class="position-relative rounded-4 shadow-4-hover bg-surface-secondary">
                                <div class="p-5 p-md-5 p-xl-10">
                                    <section>
                                        <header>
                                            <h1 class="h3 ls-tight mb-4">
                                                Welcome User
                                            </h1>
                                        </header>
                                        <p class="text-muted mb-5">
                                            A simple welcome email template for new users.
                                        </p>
                                        <footer>
                                            <a href="{{route('barua.debug.welcome_user')}}" class="font-semibold link-primary stretched-link">Check it out -></a>
                                        </footer>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative rounded-4 shadow-4-hover bg-surface-secondary">
                                <div class="p-5 p-md-5 p-xl-10">
                                    <section>
                                        <header>
                                            <h1 class="h3 ls-tight mb-4">
                                                Verify Email
                                            </h1>
                                        </header>
                                        <p class="text-muted mb-5">
                                            A simple email template for verifying user email addresses.
                                        </p>
                                        <footer>
                                            <a href="{{route('barua.debug.verify_email')}}" class="font-semibold link-primary stretched-link">Check it out -></a>
                                        </footer>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative rounded-4 shadow-4-hover bg-surface-secondary">
                                <div class="p-5 p-md-5 p-xl-10">
                                    <section>
                                        <header>
                                            <h1 class="h3 ls-tight mb-4">
                                                Forgot Password
                                            </h1>
                                        </header>
                                        <p class="text-muted mb-5">
                                            A simple email template for resetting user passwords.
                                        </p>
                                        <footer>
                                            <a href="{{route('barua.debug.forgot_password')}}" class="font-semibold link-primary stretched-link">Check it out -></a>
                                        </footer>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative rounded-4 shadow-4-hover bg-surface-secondary">
                                <div class="p-5 p-md-5 p-xl-10">
                                    <section>
                                        <header>
                                            <h1 class="h3 ls-tight mb-4">
                                                Payment Confirmation
                                            </h1>
                                        </header>
                                        <p class="text-muted mb-5">
                                            A simple email template for confirming payments.
                                        </p>
                                        <footer>
                                            <a href="{{route('barua.debug.payment_confirmation')}}" class="font-semibold link-primary stretched-link">Check it out -></a>
                                        </footer>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted text-sm font-semibold text-center my-12">
                        Copyright &copy; 2024 &middot; Made with <i class="bi bi-heart mx-1 text-danger"></i> by <a href="https://simtabi.com/">Simtabi</a>
                    </p>
                </div>
            </div>

        </main>
    </div>

    <!-- Footer -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>