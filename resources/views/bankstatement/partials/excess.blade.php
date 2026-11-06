<div class="mt-4">
    <h5>Excess Details</h5>
    @if($extraData)
        

        {{-- Files --}}
        @if($extraData->files && $extraData->files->count())
            <h6>KYC Document</h6>
            <ul>
                @foreach($extraData->files as $file)
                    <li>
                        <a href="{{ asset('storage/'.$file->filepath) }}" target="_blank">
                            {{ $file->filename }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Upload Letter --}}
        <div class="mb-3">
            <label>Upload Letter</label>
           
            @if($extraData->letter_path)
                <a href="{{ asset('storage/'.$extraData->letter_path) }}" target="_blank">
                    View Current Letter
                </a>
            @endif
        </div>
    @else
        <p class="text-muted">No excess details found.</p>
    @endif
</div>
