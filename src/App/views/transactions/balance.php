<?php include $this->resolve("partials/_header-zalogowany.php"); ?>

<section id="balance-page">

    <div class="container col-xxl-8 px-4 py-5 whole-page">
        <div
            class="mask align-items-center h-100 gradient-custom-3 py-3 px-3 login-content">

            <div class="col-md-12 mb-3">
                <?php
                $selected_period = $_SESSION['selected_period'] ?? '';
                ?>
                <form id="form_balance" method="GET" action="balance">
                    <label for="chooseDate" class="mb-1"><strong>Wybierz okres</strong>
                        <select class="form-select" id="chooseDate" name="chooseDate" onchange="handlePeriodChange(this.value)">
                            <option value="">Wybierz...</option>
                            <option value="current_month" <?= $selected_period === 'current_month' ? 'selected' : ''; ?>>Bieżący miesiąc</option>
                            <option value="last_month" <?= $selected_period === 'last_month' ? 'selected' : ''; ?>>Poprzedni miesiąc</option>
                            <option value="current_year" <?= $selected_period === 'current_year' ? 'selected' : ''; ?>>Bieżący rok</option>
                            <option value="custom" <?= $selected_period === 'custom' ? 'selected' : ''; ?>>Niestandardowe</option>
                        </select>
                    </label>

                    <div id="customDateSection" class="mt-3" style="display: <?= $selected_period === 'custom' ? 'block' : 'none'; ?>;">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Data od</label>
                                <input type="date" class="form-control" id="customStartDate" name="customStartDate" value="<?= htmlspecialchars($startDate); ?>">
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Data do</label>
                                <input type="date" class="form-control" id="customEndDate" name="customEndDate" value="<?= htmlspecialchars($endDate); ?>">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Pokaż</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class=" mb-3">
                <p><strong>Wybrano okres:</strong>
                    od <strong><?= htmlspecialchars($startDate) ?></strong>
                    do <strong><?= htmlspecialchars($endDate) ?></strong>
                </p>
            </div>

            <div class="row tables">
                <div class="col-md-6">
                    <h3>Przychody</h3>
                    <div class="table-responsive small">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="number" scope="col">#</th>
                                    <th class="date" scope="col">Data</th>
                                    <th class="category" scope="col">Kategoria</th>
                                    <th class="amount" scope="col">Kwota</th>
                                    <th class="comment" scope="col">Komentarz</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if ($incomesCount > 0) : ?>
                                    <?php foreach ($incomes as $i => $income) : ?>
                                        <tr>
                                            <td><?php echo ($i + 1); ?></td>
                                            <td><?php echo e($income['date']); ?></td>
                                            <td><?php echo e($income['category']); ?></td>
                                            <td><?php echo e($income['amount']); ?></td>
                                            <td><?php echo e($income['description']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="total-row fw-bold">
                                        <td colspan="2">Suma przychodów</td>
                                        <td class="text-end"><?php echo e($sumIncomes); ?> PLN</td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Brak przychodów w wybranym okresie.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Wydatki</h3>
                    <div class="table-responsive small">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Sposób płatności</th>
                                    <th scope="col">Kategoria</th>
                                    <th scope="col">Kwota</th>
                                    <th scope="col">Komentarz</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if ($expensesCount > 0) : ?>
                                    <?php foreach ($expenses as $i => $expense) : ?>
                                        <tr>
                                            <td><?php echo ($i + 1); ?></td>
                                            <td><?php echo e($expense['date']); ?></td>
                                            <td><?php echo e($expense['payment_method']); ?></td>
                                            <td><?php echo e($expense['category']); ?></td>
                                            <td><?php echo e($expense['amount']); ?></td>
                                            <td><?php echo e($expense['description']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="total-row fw-bold">
                                        <td colspan="2">Suma wydatków</td>
                                        <td class="text-end"><?php echo e($sumExpenses); ?> PLN</td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Brak wydatków w wybranym okresie.</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-4 order-md-1">
                <h4 class="<?php echo e($balance['balanceClass']); ?>">
                    Bilans: <strong><?php echo e($balance['amount']); ?> PLN</strong>
                </h4>
                <div class="alert <?php echo e($balance['alertClass']); ?> mt-2">
                    <?php echo e($balance['alertText']); ?>
                </div>
            </div>

            <div class="row tables">
                <div class="col-md-6">
                    <!-- WYKRES KOŁOWY - Przychody WEDŁUG KATEGORII -->
                    <?php if (!empty($incomeByCategory)): ?>
                        <div class="mt-4">
                            <h5 class="text-center mb-3">Struktura przychodów według kategorii</h5>
                            <div style="max-width: 500px; margin: 0 auto;">
                                <canvas id="incomePieChart"></canvas>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <!-- WYKRES KOŁOWY - WYDATKI WEDŁUG KATEGORII -->
                    <?php if (!empty($expenseByCategory)): ?>
                        <div class="mt-4">
                            <h5 class="text-center mb-3">Struktura wydatków według kategorii</h5>
                            <div style="max-width: 500px; margin: 0 auto;">
                                <canvas id="expensePieChart"></canvas>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>


        </div>
    </div>
    </div>
</section>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

<script>
    function handlePeriodChange(value) {
        const customSection = document.getElementById('customDateSection');
        if (value === 'custom') {
            customSection.style.display = 'block';
        } else {
            customSection.style.display = 'none';
            document.getElementById('form_balance').submit();
        }
    }
</script>

<!-- <script src="https://jsdelivr.net"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.6.1/chart.umd.min.js"></script> -->
<script src="/assets/chart.umd.min.js"></script>
<script>
    function renderPieChart(canvasId, chartData) {
        const canvas = document.getElementById(canvasId);
        if (!canvas || Object.keys(chartData).length === 0) {
            return;
        }

        const labels = Object.keys(chartData);
        const values = Object.values(chartData);

        new Chart(canvas, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#453F78', '#FFC94A', '#795458', '#969C36', '#D1DB16', '#A360DF', '#C9CBCF', '#FF6B6B',
                        '#36A2EB', '#FF6384'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed || 0;
                                return ` ${context.label}: ${value.toFixed(2).replace('.', ',')} zł`;
                            }
                        }
                    }
                }
            }
        });
    }

    const incomeData = <?= json_encode($incomeByCategory ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const expenseData = <?= json_encode($expenseByCategory ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

    renderPieChart('incomePieChart', incomeData);
    renderPieChart('expensePieChart', expenseData);
</script>

<?php include $this->resolve("partials/_footer.php"); ?>