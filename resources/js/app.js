import './bootstrap';

const $ = window.jQuery;

if ($) {
    $(function () {
        const $page = $('#student-page');

        if (!$page.length) {
            return;
        }

        const indexUrl = $page.data('index-url');
        const storeUrl = $page.data('store-url');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const $tableBody = $('#students-table-body');
        const $alert = $('#student-alert');
        const $formModal = $('#student-form-modal');
        const $deleteModal = $('#deleteModal');
        const $form = $('#student-form');
        const $saveButton = $('#save-student');

        let editingStudent = null;
        let deletingStudent = null;

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        loadStudents();

        $('#open-create-student').on('click', function () {
            openStudentForm();
        });

        $('#close-student-form').on('click', function () {
            closeStudentForm();
        });

        $('#cancel-delete-student').on('click', function () {
            closeDeleteModal();
        });

        $formModal.on('click', function (event) {
            if (event.target === this) {
                closeStudentForm();
            }
        });

        $deleteModal.on('click', function (event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        $tableBody.on('click', '.js-edit-student', function () {
            openStudentForm(getStudentFromButton(this));
        });

        $tableBody.on('click', '.js-delete-student', function () {
            deletingStudent = getStudentFromButton(this);
            $deleteModal.addClass('active');
        });

        $('#confirm-delete-student').on('click', function () {
            if (!deletingStudent) {
                return;
            }

            $.ajax({
                url: deletingStudent.delete_url,
                method: 'DELETE',
                success(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                        return;
                    }

                    closeDeleteModal();
                    showAlert(response.message || 'Student deleted successfully.', 'success');
                    loadStudents();
                },
                error(xhr) {
                    showAlert(getErrorMessage(xhr), 'error');
                },
            });
        });

        $form.on('submit', function (event) {
            event.preventDefault();
            clearFormErrors();

            const isEditing = Boolean(editingStudent);
            const formData = $form.serialize();

            $saveButton.prop('disabled', true).text(isEditing ? 'Updating...' : 'Saving...');

            $.ajax({
                url: isEditing ? editingStudent.update_url : storeUrl,
                method: isEditing ? 'PUT' : 'POST',
                data: formData,
                success(response) {
                    closeStudentForm();
                    showAlert(response.message || 'Student saved successfully.', 'success');
                    loadStudents();
                },
                error(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        showFormErrors(xhr.responseJSON.errors);
                    } else {
                        showAlert(getErrorMessage(xhr), 'error');
                    }
                },
                complete() {
                    $saveButton.prop('disabled', false).text('Save Student');
                },
            });
        });

        function loadStudents() {
            $tableBody.html('<tr><td colspan="6" style="color: #831843; font-weight: 600; text-align: center;">Loading students...</td></tr>');

            $.ajax({
                url: indexUrl,
                method: 'GET',
                success(response) {
                    renderStudents(response.students || []);
                },
                error(xhr) {
                    $tableBody.html(`<tr><td colspan="6" style="color: #ef4444; font-weight: 600; text-align: center;">${escapeHtml(getErrorMessage(xhr))}</td></tr>`);
                },
            });
        }

        function renderStudents(students) {
            if (!students.length) {
                $tableBody.html('<tr><td colspan="6" style="color: #831843; font-weight: 600; text-align: center;">No students found.</td></tr>');
                return;
            }

            const rows = students.map(function (student, index) {
                const encodedStudent = encodeURIComponent(JSON.stringify(student));

                return `
                    <tr>
                        <td style="color: #831843; font-weight: 600;">${index + 1}</td>
                        <td style="color: #333;">${escapeHtml(student.full_name)}</td>
                        <td style="color: #666;">${escapeHtml(student.email || '')}</td>
                        <td style="color: #666;">${escapeHtml(student.contact || '')}</td>
                        <td style="color: #666;">${escapeHtml(student.degree || '')}</td>
                        <td>
                            <a href="${student.show_url}" class="student-btn view">View</a>
                            <button type="button" class="student-btn edit js-edit-student" data-student="${encodedStudent}">Edit</button>
                            <button type="button" class="student-btn delete js-delete-student" data-student="${encodedStudent}">Delete</button>
                        </td>
                    </tr>
                `;
            }).join('');

            $tableBody.html(rows);
        }

        function openStudentForm(student = null) {
            editingStudent = student;
            clearFormErrors();
            $form[0].reset();

            $('#student-form-title').text(student ? 'Edit Student' : 'Add Student');
            $('#student_id').val(student?.id || '');
            $('#fname').val(student?.fname || '');
            $('#mname').val(student?.mname || '');
            $('#lname').val(student?.lname || '');
            $('#contact').val(student?.contact || '');
            $('#degree_id').val(student?.degree_id || '');
            $('#email').val(student?.email || '');

            $('#password, #password_confirmation').prop('required', !student).val('');
            $('label[for="password"]').text(student ? 'Password (leave blank to keep current)' : 'Password');
            $saveButton.text(student ? 'Update Student' : 'Save Student');
            $formModal.addClass('active');
        }

        function closeStudentForm() {
            editingStudent = null;
            $formModal.removeClass('active');
            $form[0].reset();
            clearFormErrors();
        }

        function closeDeleteModal() {
            deletingStudent = null;
            $deleteModal.removeClass('active');
        }

        function showAlert(message, type) {
            $alert
                .removeClass('success error')
                .addClass(type)
                .text(message)
                .show();

            setTimeout(function () {
                $alert.fadeOut(200);
            }, 3500);
        }

        function showFormErrors(errors) {
            Object.keys(errors).forEach(function (field) {
                $(`[data-error-for="${field}"]`).text(errors[field][0]);
            });
        }

        function clearFormErrors() {
            $('.student-form-error').text('');
        }

        function getErrorMessage(xhr) {
            return xhr.responseJSON?.message || 'Something went wrong. Please try again.';
        }

        function getStudentFromButton(button) {
            return JSON.parse(decodeURIComponent($(button).attr('data-student')));
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    });
}
