<div class="mb-3">
    <label>Bank Name *</label>
    <input type="text" name="bank_name" class="form-control" required value="{{ old('bank_name', $bankDetail->bank_name ?? '') }}">
</div>

<div class="mb-3">
    <label>Journal</label>
    <input type="text" name="journal" class="form-control" value="{{ old('journal', $bankDetail->journal ?? '') }}">
</div>

<div class="mb-3">
    <label>Cluster</label>
    <input type="text" name="cluster" class="form-control" value="{{ old('cluster', $bankDetail->cluster ?? '') }}">
</div>

<div class="mb-3">
    <label>Region</label>
    <input type="text" name="region" class="form-control" value="{{ old('region', $bankDetail->region ?? '') }}">
</div>

<div class="mb-3">
    <label>Area</label>
    <input type="text" name="area" class="form-control" value="{{ old('area', $bankDetail->area ?? '') }}">
</div>

<div class="mb-3">
    <label>State</label>
    <input type="text" name="state" class="form-control" value="{{ old('state', $bankDetail->state ?? '') }}">
</div>

<div class="mb-3">
    <label>Referred By</label>
    <input type="text" name="referred_by" class="form-control" value="{{ old('referred_by', $bankDetail->referred_by ?? '') }}">
</div>
