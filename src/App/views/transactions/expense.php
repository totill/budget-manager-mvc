<?php include $this->resolve("partials/_header-zalogowany.php"); ?>

<section id="expense-page" class="background">
    <div class="container col-xxl-8 px-4 py-5 whole-page">
        <div
            class="mask d-flex align-items-center h-100 gradient-custom-3 login-content py-3 px-3">
            <div class="col-md-12 order-md-1">
                <h4 class="mb-3">Dodaj nowy wydatek</h4>

                <?php
                if (isset($_SESSION['emptyFieldError'])) {

                    echo '<div class="invalid-feedback">' . $_SESSION['emptyFieldError'] . '</div>';
                    unset($_SESSION['emptyFieldError']);
                }
                ?>

                <form method="POST" class="needs-validation" id="expenseForm" novalidate="">
                    <?php include $this->resolve('partials/_csrf.php') ?>
                    <div class="mb-3">
                        <label for="amount">Kwota</label>
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                id="amount"
                                name="amount"
                                placeholder="Podaj kwotę w PLN"
                                value="<?php echo e($oldFormData['amount'] ?? ''); ?>">

                        </div>

                        <?php if (array_key_exists('amount', $errors)) : ?>
                            <div class="error">
                                <?php echo e($errors['amount'][0]); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="date">Data</label>

                        <div class="input-group choose-date">
                            <input value="<?php echo e($oldFormData['date'] ?? ''); ?>" name="date" type="date" />
                            <?php if (array_key_exists('date', $errors)) : ?>
                                <div class="choose-date form-control userInput labeledInput">
                                    <?php echo e($errors['date'][0]); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="payment_method">Sposób płatności</label>
                        <select
                            class="custom-select d-block w-100"
                            id="payment_method"
                            name="payment_method"
                            required>
                            <option value="">Wybierz...</option>

                            <option value="Gotówka" <?php echo ($oldFormData['payment_method'] ?? '') === 'Gotówka' ? 'selected' : '' ?>>Gotówka</option>

                            <option value="Karta kredytowa" <?php echo ($oldFormData['payment_method'] ?? '') === 'Karta kredytowa' ? 'selected' : '' ?>>Karta kredytowa</option>

                            <option value="Karta debetowa" <?php echo ($oldFormData['payment_method'] ?? '') === 'Karta debetowa' ? 'selected' : '' ?>>Karta debetowa</option>

                        </select>

                        <?php if (!empty($errors['payment_method'])) : ?>
                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                <?php echo e($errors['payment_method'][0]); ?>
                            </div>
                        <?php endif; ?>

                        <!-- <div class="invalid-feedback">
                            Należy wybrać sposób zapłaty.
                        </div> -->
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="category">Kategoria</label>
                        <select
                            class="custom-select d-block w-100"
                            id="category"
                            name="category"
                            required=>

                            <option value="">Wybierz...</option>

                            <option value="Jedzenie" <?php echo ($oldFormData['category'] ?? '') === 'Jedzenie' ? 'selected' : '' ?>>Jedzenie</option>

                            <option value="Mieszkanie" <?php echo ($oldFormData['category'] ?? '') === 'Mieszkanie' ? 'selected' : '' ?>>Mieszkanie</option>

                            <option value="Transport" <?php echo ($oldFormData['category'] ?? '') === 'Transport' ? 'selected' : '' ?>>Transport</option>

                            <option value="Telekomunikacja" <?php echo ($oldFormData['category'] ?? '') === 'Telekomunikacja' ? 'selected' : '' ?>>Telekomunikacja</option>

                            <option value="Opieka zdrowotna" <?php echo ($oldFormData['category'] ?? '') === 'Opieka zdrowotna' ? 'selected' : '' ?>>Opieka zdrowotna</option>

                            <option value="Ubranie" <?php echo ($oldFormData['category'] ?? '') === 'Ubranie' ? 'selected' : '' ?>>Ubranie</option>

                            <option value="Higiena" <?php echo ($oldFormData['category'] ?? '') === 'Higiena' ? 'selected' : '' ?>>Higiena</option>

                            <option value="Dzieci" <?php echo ($oldFormData['category'] ?? '') === 'Dzieci' ? 'selected' : '' ?>>Dzieci</option>

                            <option value="Rozrywka" <?php echo ($oldFormData['category'] ?? '') === 'Rozrywka' ? 'selected' : '' ?>>Rozrywka</option>

                            <option value="Wycieczka" <?php echo ($oldFormData['category'] ?? '') === 'Wycieczka' ? 'selected' : '' ?>>Wycieczka</option>

                            <option value="Szkolenia" <?php echo ($oldFormData['category'] ?? '') === 'Szkolenia' ? 'selected' : '' ?>>Szkolenia</option>

                            <option value="Książki" <?php echo ($oldFormData['category'] ?? '') === 'Książki' ? 'selected' : '' ?>>Książki</option>

                            <option value="Oszczędności" <?php echo ($oldFormData['category'] ?? '') === 'Oszczędności' ? 'selected' : '' ?>>Oszczędności</option>

                            <option value="Na złotą jesień, czyli emeryturę" <?php echo ($oldFormData['category'] ?? '') === 'Na złotą jesień, czyli emeryturę' ? 'selected' : '' ?>>Na złotą jesień, czyli emeryturę</option>

                            <option value="Spłata długów" <?php echo ($oldFormData['category'] ?? '') === 'Spłata długów' ? 'selected' : '' ?>>Spłata długów</option>

                            <option value="Darowizna" <?php echo ($oldFormData['category'] ?? '') === 'Darowizna' ? 'selected' : '' ?>>Darowizna</option>

                            <option value="Inne wydatki" <?php echo ($oldFormData['category'] ?? '') === 'Inne wydatki' ? 'selected' : '' ?>>Inne wydatki</option>

                        </select>

                        <?php if (!empty($errors['category'])) : ?>
                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                <?php echo e($errors['category'][0]); ?>
                            </div>
                        <?php endif; ?>

                        <!-- <div class="invalid-feedback">Należy wybrać kategorię.</div> -->
                    </div>

                    <div class="mb-3">
                        <label for="comment">Komentarz
                            <span class="text-muted">(opcjonalnie)</span></label>

                        <input value="<?php echo e($oldFormData['description'] ?? ''); ?>" name="description" type="text" class="form-control userInput labeledInput" />
                        <?php if (array_key_exists('description', $errors)) : ?>
                            <div class="bg-gray-100 mt-2 p-2 text-red-500">
                                <?php echo e($errors['description'][0]); ?>
                            </div>
                        <?php endif; ?>

                    </div>


                    <button class="btn btn-primary btn-block btn-main" type="submit">
                        Dodaj
                    </button>
                    <a href="/main-menu" class="btn btn-primary btn-block">
                        Anuluj</a>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include $this->resolve("partials/_footer.php"); ?>