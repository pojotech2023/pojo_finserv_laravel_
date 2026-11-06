@extends('layouts.app')

@section('content')
<style>
    input:focus, textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #ccc !important;
    }
</style>

<div class="container">
    <h3>Bank Statement Details</h3>

    <form action="{{ route('investors.updateInterest', $statement->id) }}" id="storeform" method="POST">
        @csrf

        {{-- Static fields --}}
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="text" class="form-control" readonly value="{{ optional($statement->date)->format('d-m-Y') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">UTR No</label>
            <input type="text" class="form-control" readonly value="{{ $statement->utrno }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Remarks</label>
            <textarea class="form-control" rows="3" readonly>{{ $statement->remarks }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" readonly value="{{ $statement->name }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="text" class="form-control" readonly value="{{ number_format($statement->amount, 2) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Action</label>
            <input type="text" class="form-control" readonly value="{{ $statement->action_type }}">
            <input type="hidden" class="form-control" name="old_action_type"value="{{ $statement->action_type }}">
        </div>
        <div class="mb-3">
            <label class="form-label">REF NO</label>
            <input type="text" class="form-control" readonly value="{{ $statement->ref_no }}">
            <input type="hidden" name="statement_id" value="{{ $statement->id }}">
            <input type="hidden" name="ref_no" value="{{ $statement->ref_no }}">
            <input type="hidden" name="conform_ref_no" value="{{ $statement->ref_no }}">
            <input type="hidden" name="investor_id" id="investor_id" value="{{ $statement->investor_id ?? $investor->id ?? '' }}">
        </div>

        {{-- Investor Selection --}}
        <div class="mb-3 position-relative">
            <label class="form-label">Investor Name</label>
            <input type="text" id="investor-name" name="name"
                   value="{{ old('name', $investor->name ?? '') }}"
                   class="form-control" placeholder="Type investor name">
            <div id="investor-suggestions"
                 class="list-group position-absolute w-100"
                 style="z-index:1000; display:none;"></div>
        </div>

        {{-- Editable Percentage --}}
        <div class="mb-3">
            <label class="form-label">Percentage</label>
            <input type="number" id="percentage" name="percentage"
                   value="{{ old('percentage', $investor->percentage ?? '') }}"
                   class="form-control" step="0.01">
        </div>

        {{-- Editable Duration Split --}}
        @php
            $duration = $investor->investment_duration ?? old('investment_duration');
            $duration_value = '';
            $duration_unit = '';
            if ($duration) {
                preg_match('/(\d+)\s*(day|week|month|year)/i', $duration, $matches);
                $duration_value = $matches[1] ?? '';
                $duration_unit = strtolower($matches[2] ?? '');
            }
        @endphp

        <div class="mb-3">
            <label class="form-label">Investment Duration *</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" id="investment_duration_value" name="investment_duration_value"
                           class="form-control"
                           value="{{ old('investment_duration_value', $duration_value) }}" min="1">
                </div>
                <div class="col-md-6">
                    <select id="investment_duration_unit" name="investment_duration_unit" class="form-control">
                        <option value="day"   {{ $duration_unit == 'day' ? 'selected' : '' }}>Day(s)</option>
                        <option value="week"  {{ $duration_unit == 'week' ? 'selected' : '' }}>Week(s)</option>
                        <option value="month" {{ $duration_unit == 'month' ? 'selected' : '' }}>Month(s)</option>
                        <option value="year"  {{ $duration_unit == 'year' ? 'selected' : '' }}>Year(s)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Interest Amount --}}
        <div class="mb-3">
            <label class="form-label">Interest Amount</label>
            <input type="text" id="interest" name="interest_amount"
                   value="{{ old('interest_amount', $investor->interest_amount ?? '') }}"
                   class="form-control">
        </div>

       {{-- <div class="mb-3">
            <label class="form-label">Duration Status</label>
            <input type="text" id="duration_status" name="duration_status"  value="{{ old('duration_status', $investor->duration_status ?? '') }}" class="form-control">
        </div>

       
        <div class="mb-3" id="received_commission_wrapper">
            <label class="form-label">Received Commission</label>
            <input type="number" step="0.01" id="received_commission" name="received_commission"
                   class="form-control"
                   value="{{ old('received_commission', $investor->received_commission ?? '') }}">
        </div>

        
        <div class="mb-3" id="interest_balance_wrapper">
            <label class="form-label">Interest Balance</label>
            <input type="text" id="interest_balance" name="interest_balance"
                   value="{{ old('interest_balance', $investor->interest_balance ?? '') }}"
                   class="form-control text-danger">
        </div>

        <input type="hidden" name="interest_status" id="interest_status_hidden">--}}

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('bank.statement') }}" class="btn btn-secondary">Back</a>
    </form>

    <!-- Loader Overlay -->
    <div id="loader-overlay" style="display:none;
            position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(255,255,255,0.6); backdrop-filter:blur(4px);
            z-index:9999; align-items:center; justify-content:center;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {

     let $statusInput = $("#duration_status");
    if ($statusInput.val()) {
        if ($statusInput.val().includes("after")) {
            $statusInput.css("background-color", "red");
        } else if ($statusInput.val().includes("to maturity")) {
            $statusInput.css("background-color", "green");
        }
    }
    let $input = $("#investor-name");
    let $suggestions = $("#investor-suggestions");

    @if($statement->investor_id)
        $.get("/investor/details", { id: "{{ $statement->investor_id }}" }, function (data) {
            if (data) {
                fillInvestorFields(data, {{ $statement->amount }}, "{{ $statement->date }}");
                @if(!empty($statement->received_commission))
                    $("#received_commission").val("{{ $statement->received_commission }}");
                    calculateBalance();
                @endif
            }
        });
    @endif

    // Search investor suggestions
    $input.on("keyup", function () {
        let query = $(this).val().trim();
        if (query.length < 1) { $suggestions.hide(); return; }
        $.get("/investor/search", { q: query }, function (data) {
            $suggestions.empty();
            if (data.length > 0) {
                data.forEach(function (name) {
                    $suggestions.append(`<a href="#" class="list-group-item list-group-item-action">${name}</a>`);
                });
                $suggestions.show();
            } else {
                $suggestions.hide();
            }
        });
    });

    // Click suggestion
    $suggestions.on("click", "a", function (e) {
        e.preventDefault();
        let selectedName = $(this).text();
        $input.val(selectedName);
        $suggestions.hide();

        $.get("/investor/details", { name: selectedName }, function (data) {
            if (data) {
                fillInvestorFields(data, {{ $statement->amount }}, "{{ $statement->date }}");
            }
        });
    });

    // Hide suggestions when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest("#investor-name, #investor-suggestions").length) {
            $suggestions.hide();
        }
    });

    // Calculate balance on commission input change
    $("#received_commission").on("input", calculateBalance);

    // Recalculate when editable fields change
    $("#percentage, #investment_duration_value, #investment_duration_unit").on("input change", function () {
        let amount = {{ $statement->amount }};
        let durationValue = parseInt($("#investment_duration_value").val()) || 0;
        let durationUnit = $("#investment_duration_unit").val();
        let percentage = parseFloat($("#percentage").val()) || 0;
        let dateStr = "{{ $statement->date }}";

        calculateInterestAndStatus(amount, durationValue, durationUnit, percentage, dateStr);
        calculateBalance();
    });

    function fillInvestorFields(data, amount, dateStr) {
        $("#investor_id").val(data.id);
        $("#percentage").val(data.percentage);

        let durationValue = parseInt(data.investment_duration);
        let durationUnit = data.investment_duration.replace(/[0-9]/g, '').trim();
        $("#investment_duration_value").val(durationValue);
        $("#investment_duration_unit").val(durationUnit);

        $("#received_commission").val(data.received_commission || '');
        calculateInterestAndStatus(amount, durationValue, durationUnit, data.percentage, dateStr);
        calculateBalance();
    }

    function calculateInterestAndStatus(amount, durationValue, durationUnit, percentage, dateStr) {
        let interest = 0;
        let startDate = new Date(dateStr);
        let maturityDate = new Date(startDate);

        switch (durationUnit.toLowerCase()) {
            case 'month': case 'months':
                interest = (amount * percentage * durationValue) / (100 * 12);
                maturityDate.setMonth(maturityDate.getMonth() + durationValue); break;
            case 'week': case 'weeks':
                interest = (amount * percentage * durationValue) / (100 * 52);
                maturityDate.setDate(maturityDate.getDate() + durationValue * 7); break;
            case 'day': case 'days':
                interest = (amount * percentage * durationValue) / (100 * 365);
                maturityDate.setDate(maturityDate.getDate() + durationValue); break;
            case 'year': case 'years':
                interest = (amount * percentage * durationValue);
                maturityDate.setFullYear(maturityDate.getFullYear() + durationValue); break;
        }

        $("#interest").val(interest.toFixed(2));
        let today = new Date();
        let diffDays = Math.floor((today - maturityDate) / (1000 * 60 * 60 * 24));

        if (diffDays >= 0) {
            $("#duration_status").val(`${diffDays} days after maturity`).css("background-color", "red");
        } else {
            $("#duration_status").val(`${Math.abs(diffDays)} days to maturity`).css("background-color", "green");
        }
    }

    function calculateBalance() {
        let interest = parseFloat($("#interest").val()) || 0;
        let received = parseFloat($("#received_commission").val()) || 0;
        let balance = interest - received;
        $("#interest_balance").val(balance.toFixed(2));
    }
});

// AJAX form submit
$(document).ready(function () {
    $("#storeform").on("submit", function (e) {
        e.preventDefault();
        $("#loader-overlay").css("display", "flex").hide().fadeIn(200);

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $("#loader-overlay").fadeOut(200);
                if (response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                $("#loader-overlay").fadeOut(200);
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
@endsection
