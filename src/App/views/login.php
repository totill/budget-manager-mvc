<?php include $this->resolve("partials/_header-niezalogowany.php"); ?>

<section id="sign-in-page" class="background">
    <div class="container col-xxl-8 px-4 py-5 whole-page">
        <div
            class="row flex-lg-row-reverse align-items-center mx-2 px-3 py-2 login-content">
            <h1 class="display-5 fw-bold lh-1 mb-3 text-center">
                Chcesz wreszcie zapanować <br />
                nad swoimi wydatkami?
            </h1>

            <div class="whole-content">
                <div class="row row-cols-2">
                    <div class="col-xl-6 col-sm-12">
                        <div class="login-text">
                            <p class="lead">
                                Szukasz prostego narzędzia do zarządzania swoim budżetem?
                                Chcesz w końcu przestać żyć "od pierwszego do pierwszego"?
                                Masz poczucie, że pieniądze przelatują Ci przez palce?
                                Zacznij od założenia konta i obejmij kontrolę nad własnymi
                                finansami!
                            </p>
                        </div>
                    </div>
                    <div
                        class="col-xl-6 col-sm-12 d-flex justify-content-center align-items-center col-sm-12 mb-2">
                        <div class="disclaimer fw-bold">
                            Zaufało nam już 15 użytkowników!
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-center">
                            <div>
                                <h2 class="h3 mb-3 fw-normal">
                                    Masz już konto? Zaloguj się.
                                </h2>

                                <form method="POST">
                                    <?php include $this->resolve('partials/_csrf.php') ?>
                                    <div class="form-floating pb-2">
                                        <input
                                            type="email"
                                            value="<?php echo e($oldFormData['email'] ?? ''); ?>"
                                            name="email"
                                            class="form-control"
                                            id="floatingInput"
                                            placeholder="name@example.com" />
                                        <?php if (array_key_exists('email', $errors)): ?>
                                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                                <?php echo e($errors['email'][0]); ?>
                                            </div>
                                        <?php endif; ?>
                                        <label for="floatingInput">E-mail</label>
                                    </div>
                                    <div class="form-floating">
                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            id="floatingPassword"
                                            placeholder="Password" />
                                        <?php if (array_key_exists('password', $errors)): ?>
                                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                                <?php echo e($errors['password'][0]); ?>
                                            </div>
                                        <?php endif; ?>
                                        <label for="floatingPassword">Hasło</label>
                                    </div>
                                    <div class="form-check text-start my-3">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="remember-me"
                                            id="checkDefault" />
                                        <label class="form-check-label" for="checkDefault">
                                            Zapamiętaj mnie
                                        </label>
                                    </div>

                                    <button class="btn btn-primary w-100 py-2" type="submit">Zaloguj się</button>


                                </form>


                                <h2 class="h3 my-3 fw-normal">Lub:</h2>

                                <a
                                    href="/register"
                                    class="btn btn-primary w-100 py-2 mb-3"
                                    type="submit">

                                    Utwórz nowe konto

                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-sm-12 mb-3">
                        <div class="d-flex justify-content-center">
                            <div class="image-cropper">
                                <img
                                    src="/images/money-saving-jar.png"
                                    alt="money in the jar"
                                    class="background-image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $this->resolve("partials/_footer.php"); ?>