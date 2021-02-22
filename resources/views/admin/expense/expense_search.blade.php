@extends('admin.layouts.master')

@section('content_header')
<style>
.modal-body {
    overflow : auto;
}
</style>
@endsection

@section('content_body')

    <!-- markup to be injected -->
    <!-- search form -->
    <h2 class="text-center display-3">Search Expenses</h2>
    <form action="enhanced-results.html" data-select2-id="13">
        <div class="row" data-select2-id="12">
            <div class="col-md-10 offset-md-1" data-select2-id="11">
                <div class="row" data-select2-id="10" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;">
                    <!-- Type -->
                    <div class="col-5">
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control type" name="type">
                                <option value="">Select type</option>
                                <option value="All">All</option>
                                <option value="Transport">Transport</option>
                                <option value="Riders Fuel">Riders Fuel</option>
                                <option value="Marketing Expense">Marketing expense</option>
                                <option value="Salary & wages">Salary & wages</option>
                                <option value="Staff entertainment">Staff entertainment</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Repair and maintenance">Repair and maintenance</option>
                                <option value="Other charges">Other charges</option>
                                <option value="Commission">Commission</option>
                                <option value="Stock damage">Stock damage</option>
                                <option value="Stock misplace">Stock misplace</option>
                                <option value="Stock theft">Stock theft</option>
                                <option value="Costumer discount">Costumer discount</option>
                            </select>
                        </div>
                    </div>
                    <!-- date from -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Date(from):</label>
                            <input type="date" class="form-control date_from" name="date_from">
                        </div>
                    </div>
                    <!-- date to -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Date(to):</label>
                            <input type="date" class="form-control date_to" name="date_to">
                        </div>
                    </div>
                    <!-- search button -->
                    <div class="col-1">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button type="button" class="btn btn-block btn-primary form-control fetch_expenses" disabled="disabled"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <!-- Ledger view -->
    <div class="modal fade" id="detailLedgerModal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title" id="detailLedgerModalLabel">Ledger</h5>
                <!-- generate excel -->
                <div class="text-right">
                    <button type="button" class="btn btn-success generate_expenses_excel">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-condensed table-sm" style="overflow-x:auto;">
                <thead>
                    <!-- outstanding balance row -->
                    <tr class="table-info">
                    <td></td>
                    <td class="text-bold">Total</td>
                    <td class="detail_total"></td>
                    </tr>
                    <!-- headers -->
                    <tr>
                    <th>Transaction Date</th>
                    <th>Amount</th>
                    <th>Details</th>
                    </tr>
                </thead>
                <tbody class="ledger_wrapper">
                    <tr>
                        <td>12.12.12</td>
                        <td>444</td>
                        <td>jaksd aksj hakjsdaskj akjsd jkashd</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- dummy form -->
    <form id="dummy_form" action="{{route('generate_expenses_excel')}}" method="POST" target="_blank" hidden>
        @csrf
    </form>

    <script>
        $(document).ready(function(){
            // persistent active sidebar
            var element = $('li a[href*="'+ window.location.pathname +'"]');
            element.parent().parent().parent().addClass('menu-open');
            element.addClass('active');

            // global vars
            var expenses = {};
            var total = 0;
            var wild_card = 0;

            // fetch expense
            function fetch_expenses(type, date_from, date_to){
                $.ajax({
                    url: "<?php echo(route('fetch_expenses')); ?>",
                    type: 'GET',
                    async: false,
                    data: {type: type, date_from: date_from, date_to: date_to},
                    dataType: 'JSON',
                    success: function (data) {
                        expenses = data.expenses;
                        total = data.total;
                    }
                });
            }

            // check if all fields are filled
            function check_fields(){
                // get all parameteres
                var type = $('.type').val();
                var date_from = $('.date_from').val();
                var date_to = $('.date_to').val();

                if(type && date_from && date_to){
                    $('.fetch_expenses').removeAttr('disabled');
                }
                else{
                    $('.fetch_expenses').prop('disabled', true);
                }
            }

            // on type change
            $('.type').on('change', function(){check_fields();})
            $('.date_from').on('change', function(){check_fields();})
            $('.date_to').on('change', function(){check_fields();})

            // on search expenses click
            $('.fetch_expenses').on('click', function(){
                // get all parameteres
                var type = $('.type').val();
                var date_from = $('.date_from').val();
                var date_to = $('.date_to').val();

                // fetch filtered expenses
                fetch_expenses(type, date_from, date_to);

                // set ledger modal title
                $('#detailLedgerModalLabel').html('Expense type: '+type+' <small>(' + new Date(date_from).toDateString() + ' - '+new Date(date_to).toDateString()+')</small>');

                // empty wrapper
                $('.ledger_wrapper').html('');

                // if no entries
                if(expenses.length == 0){
                    // no entries row
                    $('.ledger_wrapper').prepend('<tr class="table-warning"><td class="text-center" colspan=3>No Expense Entries</td></tr>');
                    // set total amount
                    $('.detail_total').html('Rs. 0');
                }
                // else
                else{
                    // if wild card
                    wild_card = ($('.type').val() == 'All') ? 1 : 0;
                    
                    // append ledger entries
                    for(var i = 0; i < expenses.length; i++){
                        $('.ledger_wrapper').prepend('<tr><td class="transaction_dates">'+new Date(expenses[i].date).toDateString() +((wild_card == 1) ? (' <strong>('+expenses[i].type+')</strong>') : '')+'</td><td class="amounts">Rs.'+expenses[i].amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td class="details">'+(expenses[i].detail ? expenses[i].detail : '')+'</td></tr>');
                    }
                    // set total amount
                    $('.detail_total').html('Rs. ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }


                $('#detailLedgerModal').modal('show');
                // new Date(client.ledgers[i].transaction_date).toDateString()
            });

            // on generate_expenses_excel click
            $('.generate_expenses_excel').on('click', function(){
                // transaction_dates
                $('.transaction_dates').each(function(){
                    $('#dummy_form').append('<input name="transaction_dates[]" value="'+$(this).text()+'"></input>')
                });
                // amounts
                $('.amounts').each(function(){
                    $('#dummy_form').append('<input name="amounts[]" value="'+$(this).text()+'"></input>')
                });
                // details
                $('.details').each(function(){
                    $('#dummy_form').append('<input name="details[]" value="'+$(this).text()+'"></input>')
                });
                // title
                $('#dummy_form').append('<input name="title" value="'+$('#detailLedgerModalLabel').text()+'"></input>')
                // total
                $('#dummy_form').append('<input name="total" value="'+$('.detail_total').text()+'"></input>')
                // submit
                $('#dummy_form').submit();
            });
        });
    </script>

@endsection('content_body')