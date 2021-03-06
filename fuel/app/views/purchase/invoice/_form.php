<?= Form::open(array("class"=>"form-horizontal", "autocomplete" => "off")); ?>

	<div class="form-group">
        <div class="col-md-6">

        </div>
        <div class="col-md-3">
			<?= Form::label('Invoice no.', 'id', array('class'=>'control-label')); ?>
            <?= Form::input('id', Input::post('id', isset($purchase_invoice) ? $purchase_invoice->id : 
                            Model_Purchase_Invoice::getNextSerialNumber()), 
                            array('class' => 'col-md-4 form-control', 'readonly' => true)); ?>
		</div>
		<div class="col-md-3">
			<?= Form::label('Status', 'status', array('class'=>'control-label')); ?>
			<?= Form::hidden('status', Input::post('status', isset($purchase_invoice) ? $purchase_invoice->status : Model_Purchase_Invoice::INVOICE_STATUS_OPEN)); ?>
            <?= Form::select('status_list', Input::post('status_list', isset($purchase_invoice) ? $purchase_invoice->status : ''), 
                            Model_Purchase_Invoice::$invoice_status, 
                            array('class' => 'col-md-4 form-control', 'disabled' => true)); ?>
		</div>
	</div>
	<div class="form-group">
        <div class="col-md-6">

        </div>
		<div class="col-md-3">
			<?= Form::label('Issue date', 'issue_date', array('class'=>'control-label')); ?>
            <?= Form::input('issue_date', Input::post('issue_date', isset($purchase_invoice) ? $purchase_invoice->issue_date : date('Y-m-d')), 
                            array('class' => 'col-md-4 form-control datepicker', 'readonly' => isset($purchase_invoice) ? true : false)); ?>
		</div>
		<div class="col-md-3">
			<?= Form::label('Due date', 'due_date', array('class'=>'control-label')); ?>
            <?= Form::input('due_date', Input::post('due_date', isset($purchase_invoice) ? $purchase_invoice->due_date :  date('Y-m-d')), 
                            array('class' => 'col-md-4 form-control datepicker', 'readonly' => isset($purchase_invoice) ? true : false)); ?>
		</div>
	</div>
	<div class="form-group">
    <div class="col-md-6">
			<?= Form::label('Supplier', 'supplier_name', array('class'=>'control-label')); ?>
            <?= Form::input('supplier_name', Input::post('supplier_name', isset($purchase_invoice) ? $purchase_invoice->supplier_name : ''), 
                            array('class' => 'col-md-4 form-control', 'readonly' => true)); ?>
        </div>
        <div class="col-md-6">
			<?= Form::label('Shipping address', 'shipping_address', array('class'=>'control-label')); ?>
            <?= Form::input('shipping_address', Input::post('shipping_address', isset($purchase_invoice) ? $purchase_invoice->shipping_address : ''), 
                            array('class' => 'col-md-4 form-control')); ?>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <br>
	<ul id="doc_detail" class="nav nav-tabs">
		<li>
			<a id="items-tab" data-toggle="tab" href="#items">Items</a>
		</li>
		<li>
			<a id="payments-tab" data-toggle="tab" href="#payments">Payments</a>
		</li>
	</ul>
    <!-- <br> -->
	<div id="doc_tabs" class="tab-content">
		<div id="items" class="tab-pane fade">
			<?= render('purchase/invoice/item/index', array('purchase_invoice_items' => isset($purchase_invoice) ? $purchase_invoice->items : array())); ?>
		</div>
		<div id="payments" class="tab-pane fade">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-2">Receipt no.</th>
						<th class="col-md-2">Date</th>
						<th class="col-md-6">Description</th>
						<th class="col-md-2 text-right">Amount</th>
					</tr>
				</thead>
				<tbody>
        <?php 
            if (isset($purchase_invoice) && !empty($purchase_invoice->receipts)) :
                foreach ($purchase_invoice->receipts as $item): ?>
                    <tr class="<?= $item->amount > 0 ? : 'strikeout text-muted' ?>">
                        <td><?= Html::anchor('accounts/payment/receipt/edit/'.$item->id, $item->reference); ?></td>
                        <td><?= $item->date; ?></td>
                        <td><?= $item->description; ?></td>
                        <td class="text-right"><?= number_format($item->amount, 2); ?></td>
                    </tr>
        <?php 
                endforeach;
            else : ?>
                    <tr id="no_data"><td class="text-muted text-center" colspan="4">No data</td></tr>
        <?php
            endif ?>
				</tbody>
			</table>
		</div>
	</div>
    <?= Form::hidden('fdesk_user', Input::post('fdesk_user', isset($purchase_invoice) ? $purchase_invoice->fdesk_user : $uid)); ?>
    <br>
	<div class="row">
		<div class="col-md-6">
            <div class="form-group">
                <div class="col-md-12">
                    <?= Form::label('Notes', 'notes', array('class'=>'control-label')); ?>
                    <?= Form::textarea('notes', Input::post('notes', isset($purchase_invoice) ? $purchase_invoice->notes : ''), 
                                        array('class' => 'col-md-4 form-control', 'rows' => 5)); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">

                </div>
            </div>
        </div>
		<div class="col-md-6">
            <div class="form-group">
                <div class="col-md-6">
                    <?= Form::label('Advance Paid', 'advance_paid', array('class'=>'control-label')); ?>
                    <?= Form::input('advance_paid', Input::post('advance_paid', isset($purchase_invoice) ? 
                                    number_format($purchase_invoice->advance_paid, 0, '.', '') : 0),
                                    array('class' => 'col-md-4 form-control text-number', 'readonly' => true)); ?>
                    <?php Form::label('Discount Amount', 'disc_total', array('class'=>'control-label')); ?>
                    <?= Form::hidden('disc_total', Input::post('disc_total', isset($purchase_invoice) ? 
                                    number_format($purchase_invoice->disc_total, 0, '.', '') : 0),
                                    array('class' => 'col-md-4 form-control text-number')); ?>
                </div>
                <div class="col-md-6">
                    <?= Form::label('Amount Due', 'amount_due', array('class'=>'control-label')); ?>
                    <?= Form::input('amount_due', Input::post('amount_due', isset($purchase_invoice) ? 
                                    number_format($purchase_invoice->amount_due, 0, '.', '') : 0),
                                    array('class' => 'col-md-4 form-control text-number', 'readonly' => true)); ?>
                </div>
                <?= Form::hidden('tax_total', Input::post('tax_total', isset($purchase_invoice) ? 
                                number_format($purchase_invoice->tax_total, 0, '.', '') : 0.0)); ?>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <?= Form::label('Amount Paid', 'amount_paid', array('class'=>'control-label')); ?>
                    <?= Form::input('amount_paid', Input::post('amount_paid', isset($purchase_invoice) ? 
                                    number_format($purchase_invoice->amount_paid, 0, '.', '') : 0),
                                    array('class' => 'col-md-4 form-control text-number', 'readonly' => true)); ?>
                </div>
                <div class="col-md-6">
                    <?= Form::label('Balance Due', 'balance_due', array('class'=>'control-label')); ?>
                    <?= Form::input('balance_due', Input::post('balance_due', isset($purchase_invoice) ? 
                                    number_format($purchase_invoice->balance_due, 0, '.', '') : 0),
                                    array('class' => 'col-md-4 form-control text-number', 'readonly' => true)); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <?= Form::label('Paid Status', 'paid_status', array('class'=>'control-label')); ?>
                    <?= Form::hidden('paid_status', Input::post('paid_status', isset($purchase_invoice) ? $purchase_invoice->paid_status : '')); ?>
                    <?= Form::select('paid_status_list', Input::post('paid_status_list', 
                            isset($purchase_invoice) ? $purchase_invoice->paid_status : ''), 
                            Model_Purchase_Invoice::$invoice_paid_status, 
                            array('class' => 'col-md-4 form-control', 'disabled' => true)); ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
	<div class="form-group">
		<div class="col-md-6">
			<!-- <button class="btn btn-success" data-bind='click: save'>Save</button> -->
			<?= Form::submit('submit', isset($purchase_invoice) ? 'Update' : 'Create', array('class' => 'btn btn-primary')); ?>
		</div>
		<div class="col-md-6">
			<div class="pull-right btn-group">
            <?php 
                if (isset($purchase_invoice)) :
                    if ($purchase_invoice->status == 'O') : ?>
                        <a href="<?= Uri::create('accounts/payment/receipt/create/' . $purchase_invoice->id); ?>" class="btn btn-default ">Add payment</a>
            <?php 
                    endif;
                endif ?>
			</div>
		</div>
	</div>

<?= Form::close(); ?>

<script>
	$('#doc_detail a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
	})
	$('#doc_detail a:first').tab('show')

    // Fetch dependent drop down list options
    $('#form_source').on('change', function() { 
        $.ajax({
            type: 'post',
            url: '/accounts/purchases-invoice/get-source-list-options',
            // dataType: 'json',
            data: {
                // console.log($(this).val());
                'source': $(this).val(),
            },
            success: function(listOptions) 
            {
                var selectOptions = '<option value="" selected></option>';
                $.each(JSON.parse(listOptions), function(index, listOption)               
                {
                    selectOptions += '<option value="' + index + '">' + listOption + '</option>';
                });
                $('#form_source_id').html(selectOptions);
            },
            error: function(jqXhr, textStatus, errorThrown) {
                console.log(errorThrown)
            }
        });
    });

    $('#form_source_id').on('change', function() { 
        $.ajax({
            type: 'post',
            url: '/accounts/purchases-invoice/get-source-info',
            // dataType: 'json',
            data: {
                // console.log($(this).val());
                'source': $('#form_source').val(),
                'source_id': $(this).val(),
            },
            success: function(data) 
            {
                // console.log(data);
                data = JSON.parse(data);
                $('#form_customer_name').val(data.customer_name);
                $('#form_billing_address').val(data.email_address);
            },
            error: function(jqXhr, textStatus, errorThrown) {
                console.log(errorThrown)
            }
        });
    });

</script>