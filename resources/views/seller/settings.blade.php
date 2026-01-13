@extends('layouts.seller')

@section('title', 'Settings - KidsStore Seller')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Store Settings</h1>

            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="storeName" class="form-label">Store Name</label>
                                    <input type="text" class="form-control" id="storeName" value="KidsStore">
                                </div>
                                <div class="mb-3">
                                    <label for="storeEmail" class="form-label">Store Email</label>
                                    <input type="email" class="form-control" id="storeEmail" value="info@kidsstore.com">
                                </div>
                                <div class="mb-3">
                                    <label for="storePhone" class="form-label">Store Phone</label>
                                    <input type="tel" class="form-control" id="storePhone" value="+1 234 567 8900">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="storeAddress" class="form-label">Store Address</label>
                                    <textarea class="form-control" id="storeAddress" rows="3">123 Kids Street, Toy City, TC 12345</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select class="form-control" id="currency">
                                        <option value="USD">USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                        <option value="GBP">GBP (£)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection