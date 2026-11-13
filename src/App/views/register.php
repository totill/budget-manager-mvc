<?php include $this->resolve("partials/_header-niezalogowany.php"); ?>

<section id="register-page" class="background">
    <div class="container col-xxl-8 px-4 py-5 whole-page">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div
                    class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card login-content" style="border-radius: 15px">
                            <div class="card-body p-5">
                                <div class="image-cropper">
                                    <img
                                        src="/images/money-saving-jar.png"
                                        alt="money in the jar"
                                        class="background-image" />
                                </div>

                                <h2 class="text-center mb-3">Utwórz nowe konto</h2>

                                <form method="POST">
                                    <?php include $this->resolve('partials/_csrf.php') ?>
                                    <div data-mdb-input-init class="form-outline mb-3">
                                        <input
                                            type="text"
                                            value="<?php echo e($oldFormData['name'] ?? ''); ?>"
                                            name="name"
                                            id="form3Example1cg"
                                            class="form-control form-control-lg"
                                            placeholder="Imię" />

                                    </div>

                                    <?php if (array_key_exists('name', $errors)) : ?>
                                        <div class="error">
                                            <?php echo e($errors['name'][0]); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div data-mdb-input-init class="form-outline mb-3">
                                        <input
                                            type="email"
                                            value="<?php echo e($oldFormData['email'] ?? ''); ?>"
                                            name="email"
                                            id="form3Example3cg"
                                            class="form-control form-control-lg"
                                            placeholder="Adres e-mail" />
                                        <?php if (array_key_exists('email', $errors)) : ?>
                                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                                <?php echo e($errors['email'][0]); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-3">
                                        <input
                                            type="password"
                                            name="password"
                                            id="form3Example4cg"
                                            class="form-control form-control-lg"
                                            placeholder="Hasło" />
                                        <?php if (array_key_exists('password', $errors)) : ?>
                                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                                <?php echo e($errors['password'][0]); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-3">
                                        <input
                                            type="password"
                                            name="confirmPassword"
                                            id="form3Example4cdg"
                                            class="form-control form-control-lg"
                                            placeholder="Powtórz hasło" />
                                        <?php if (array_key_exists('confirmPassword', $errors)) : ?>
                                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                                <?php echo e($errors['confirmPassword'][0]); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <label>
                                        <input <?php echo $oldFormData['tos'] ?? false ? 'checked' : ''; ?> type="checkbox" name="tos" class="mb-3" /> Akceptuję regulamin
                                    </label>


                                    <button class="btn btn-primary w-100 py-2 mb-3" type="submit">Zarejestruj się</button>

                                    <p class="text-center mt-4 mb-2">Masz już konto?</p>
                                    <a
                                        href="/login"
                                        class="btn btn-primary w-100 py-2 mb-3"
                                        type="submit">
                                        Zaloguj się
                                    </a>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $this->resolve("partials/_footer.php"); ?>