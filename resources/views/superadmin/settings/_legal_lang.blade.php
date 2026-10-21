{{--
    A single editable language block for a legal document.

    Variables: $docKey, $i, $code, $label, $body
    Note: $docKey / $i may be placeholders (__DOC__ / __I__) when used as a JS template.
--}}
<div class="lang-block">
    <div class="lang-block-head">
        <div class="lang-field" style="width:120px;">
            <label class="form-label" style="font-size:.66rem;">Language Code</label>
            <input type="text" class="form-control" style="font-family:'JetBrains Mono',monospace;"
                   name="documents[{{ $docKey }}][{{ $i }}][code]"
                   value="{{ $code }}" placeholder="en" maxlength="10">
        </div>
        <div class="lang-field grow">
            <label class="form-label" style="font-size:.66rem;">Language Label</label>
            <input type="text" class="form-control"
                   name="documents[{{ $docKey }}][{{ $i }}][label]"
                   value="{{ $label }}" placeholder="English">
        </div>
        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-lang" style="margin-bottom:0;align-self:flex-end;">
            <i class="bi bi-trash"></i> Remove
        </button>
    </div>

    <div class="two-col" style="gap:.75rem;align-items:start;">
        <div>
            <div class="md-preview-label"><i class="bi bi-markdown me-1"></i>Markdown</div>
            <textarea name="documents[{{ $docKey }}][{{ $i }}][body]"
                      class="md-editor"
                      placeholder="## Heading&#10;&#10;Write your content here...">{{ $body }}</textarea>
        </div>
        <div>
            <div class="md-preview-label"><i class="bi bi-eye me-1"></i>Live Preview</div>
            <div class="md-preview"></div>
        </div>
    </div>
</div>
