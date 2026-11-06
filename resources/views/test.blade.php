<form action="{{ route('bank.statements.refresh') }}" method="POST"
      onsubmit="return confirm('Are you sure you want to refresh bank statements?')">
    @csrf
    <button type="submit" class="btn btn-danger">
        Refresh Bank Statements
    </button>
</form>
