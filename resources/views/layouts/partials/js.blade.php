<script src="{{ asset('assets/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>

<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/popper.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/js/hammer.js') }}"></script>
<script src="{{ asset('assets/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
<script src="{{ asset('assets/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

@php
    $csrfToken = csrf_token();
@endphp

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const httpClient = {
        async post(url, data) {
            try {
                const response = await axios.post(url, data, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ $csrfToken }}'
                    }
                });
                return response.data;
            } catch (error) {
                throw error;
            }
        },

        async delete(url) {
            try {
                const response = await axios.delete(url, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ $csrfToken }}'
                    }
                });
                return response.data;
            } catch (error) {
                throw error;
            }
        }
    };
</script>


@stack('js')
