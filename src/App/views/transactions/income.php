<?php include $this->resolve("partials/_header-zalogowany.php"); ?>

<section id="income-page" class="background">
    <div class="container col-xxl-8 px-4 py-5 whole-page">
        <div
            class="mask d-flex align-items-center h-100 gradient-custom-3 login-content py-3 px-3">
            <div class="col-md-12 order-md-1">
                <h4 class="mb-3">Dodaj nowy przychód</h4>

                <?php
                if (isset($_SESSION['emptyFieldError'])) {

                    echo '<div class="invalid-feedback">' . $_SESSION['emptyFieldError'] . '</div>';
                    unset($_SESSION['emptyFieldError']);
                }
                ?>

                <form method="POST" class="needs-validation" id="incomeForm" novalidate="">
                    <?php include $this->resolve('partials/_csrf.php') ?>
                    <div class="mb-3">
                        <label for="amount">Kwota</label>
                        <div class="input-group">
                            <input
                                type="number"
                                class="form-control userInput labeledInput"
                                id="amount"
                                name="amount"
                                step="0.01"
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
                        <label for="category">Kategoria</label>
                        <select
                            class="custom-select d-block w-100 form-control userInput labeledInput"
                            id="category"
                            name="category"
                            required>

                            <option value="">Wybierz...</option>

                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= e($cat) ?>" <?= ($oldFormData['category'] ?? '') === $cat ? 'selected' : '' ?>>
                                    <?= e($cat) ?>
                                </option>
                            <?php endforeach; ?>

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
                        <!-- 
                        <textarea class="form-control userInput labeledInput" name="incomeComment" maxlength="100" rows="5" placeholder="Treść komentarza"></textarea> -->

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