const $ = window.jQuery;

function getErrorMessage(xhr) {
    return xhr.responseJSON?.message || 'Something went wrong. Please try again.';
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function notifyCrudChange(moduleName) {
    localStorage.setItem('ajax-crud-refresh', `${moduleName}:${Date.now()}`);
}

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
        setInterval(function () {
            loadStudents(true);
        }, 5000);

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

        $tableBody.on('click', '.js-view-student', function () {
            openStudentDetails(getStudentFromButton(this));
        });

        $tableBody.on('click', '.js-delete-student', function () {
            deletingStudent = getStudentFromButton(this);
            $deleteModal.addClass('active');
        });

        $('#close-student-details').on('click', function () {
            $('#student-details-modal').removeClass('active');
        });

        $('#student-details-modal').on('click', function (event) {
            if (event.target === this) {
                $('#student-details-modal').removeClass('active');
            }
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
                    notifyCrudChange('students');
                    loadStudents(true);
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
                    notifyCrudChange('students');
                    loadStudents(true);
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

        window.addEventListener('storage', function (event) {
            if (event.key === 'ajax-crud-refresh' && event.newValue?.includes('students')) {
                loadStudents(true);
            }
        });

        function loadStudents(silent = false) {
            if (!silent) {
                $tableBody.html('<tr><td colspan="6" style="color: #831843; font-weight: 600; text-align: center;">Loading students...</td></tr>');
            }

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
                            <button type="button" class="student-btn view js-view-student" data-student="${encodedStudent}">View</button>
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

        function openStudentDetails(student) {
            $('#detail-name').text(student.full_name || '');
            $('#detail-email').text(student.email || 'N/A');
            $('#detail-contact').text(student.contact || 'N/A');
            $('#detail-course').text(student.degree || 'N/A');
            $('#detail-first-name').text(student.fname || 'N/A');
            $('#detail-middle-name').text(student.mname || 'N/A');
            $('#detail-last-name').text(student.lname || 'N/A');
            $('#student-details-modal').addClass('active');
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

        function getStudentFromButton(button) {
            return JSON.parse(decodeURIComponent($(button).attr('data-student')));
        }
    });

    $(function () {
        const $page = $('#user-page');

        if (!$page.length) {
            return;
        }

        const indexUrl = $page.data('index-url');
        const storeUrl = $page.data('store-url');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const $tableBody = $('#users-table-body');
        const $alert = $('#user-alert');
        const $formModal = $('#user-form-modal');
        const $detailsModal = $('#user-details-modal');
        const $deleteModal = $('#delete-user-modal');
        const $form = $('#user-form');
        const $saveButton = $('#save-user');

        let editingUser = null;
        let deletingUser = null;

        $.ajaxSetup({
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        loadUsers(true);
        toggleUserStudentFields();

        $('#open-create-user').on('click', function () {
            openUserForm();
        });

        $('#close-user-form').on('click', function () {
            closeUserForm();
        });

        $('#close-user-details').on('click', function () {
            closeUserDetails();
        });

        $('#cancel-delete-user').on('click', function () {
            closeUserDelete();
        });

        $('#user_role').on('change', function () {
            toggleUserStudentFields();
        });

        $formModal.add($detailsModal).add($deleteModal).on('click', function (event) {
            if (event.target !== this) {
                return;
            }

            $(this).removeClass('active');
        });

        $tableBody.on('click', '.js-view-user', function () {
            openUserDetails(getUserFromButton(this));
        });

        $tableBody.on('click', '.js-edit-user', function () {
            openUserForm(getUserFromButton(this));
        });

        $tableBody.on('click', '.js-delete-user', function () {
            deletingUser = getUserFromButton(this);
            $deleteModal.addClass('active');
        });

        $('#confirm-delete-user').on('click', function () {
            if (!deletingUser) {
                return;
            }

            $.ajax({
                url: deletingUser.delete_url,
                method: 'DELETE',
                success(response) {
                    closeUserDelete();
                    showUserAlert(response.message || 'User deleted successfully.', 'success');
                    notifyCrudChange('users');
                    loadUsers(true);
                },
                error(xhr) {
                    showUserAlert(getErrorMessage(xhr), 'error');
                },
            });
        });

        $form.on('submit', function (event) {
            event.preventDefault();
            clearUserFormErrors();

            const isEditing = Boolean(editingUser);

            $saveButton.prop('disabled', true).text(isEditing ? 'Updating...' : 'Saving...');

            $.ajax({
                url: isEditing ? editingUser.update_url : storeUrl,
                method: isEditing ? 'PUT' : 'POST',
                data: $form.serialize(),
                success(response) {
                    closeUserForm();
                    showUserAlert(response.message || 'User saved successfully.', 'success');
                    notifyCrudChange('users');
                    loadUsers(true);
                },
                error(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        showUserFormErrors(xhr.responseJSON.errors);
                    } else {
                        showUserAlert(getErrorMessage(xhr), 'error');
                    }
                },
                complete() {
                    $saveButton.prop('disabled', false).text(editingUser ? 'Update User' : 'Save User');
                },
            });
        });

        window.addEventListener('storage', function (event) {
            if (event.key === 'ajax-crud-refresh' && event.newValue?.includes('users')) {
                loadUsers(true);
            }
        });

        function loadUsers(silent = false) {
            if (!silent) {
                $tableBody.html('<tr><td colspan="7" style="padding: 2rem; text-align: center; color: #6b7280;">Loading users...</td></tr>');
            }

            $.ajax({
                url: indexUrl,
                method: 'GET',
                timeout: 15000,
                success(response) {
                    renderUsers(response.users || []);
                },
                error(xhr) {
                    $tableBody.html(`<tr><td colspan="7" style="padding: 2rem; text-align: center; color: #ef4444;">${escapeHtml(getErrorMessage(xhr))}</td></tr>`);
                },
            });
        }

        function renderUsers(users) {
            if (!users.length) {
                $tableBody.html('<tr><td colspan="7" style="padding: 2rem; text-align: center; color: #6b7280;">No users yet. Create one to get started.</td></tr>');
                return;
            }

            const rows = users.map(function (user, index) {
                const encodedUser = encodeURIComponent(JSON.stringify(user));

                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(user.username)}</td>
                        <td>${escapeHtml(user.email)}</td>
                        <td>${escapeHtml(user.role_label)}</td>
                        <td>${escapeHtml(user.profile_status)}</td>
                        <td>${escapeHtml(user.posts_count)}</td>
                        <td>
                            <button type="button" class="user-btn view js-view-user" data-user="${encodedUser}">View</button>
                            <button type="button" class="user-btn edit js-edit-user" data-user="${encodedUser}">Edit</button>
                            <button type="button" class="user-btn delete js-delete-user" data-user="${encodedUser}">Delete</button>
                        </td>
                    </tr>
                `;
            }).join('');

            $tableBody.html(rows);
        }

        function openUserForm(user = null) {
            editingUser = user;
            clearUserFormErrors();
            $form[0].reset();

            $('#user-form-title').text(user ? 'Edit User' : 'New User');
            $('#user_id').val(user?.id || '');
            $('#user_role').val(user?.role || 'student');
            $('#user_username').val(user?.username || '');
            $('#user_email').val(user?.email || '');
            $('#user_fname').val(user?.student?.fname || user?.teacher?.fname || '');
            $('#user_mname').val(user?.student?.mname || user?.teacher?.mname || '');
            $('#user_lname').val(user?.student?.lname || user?.teacher?.lname || '');
            $('#user_contact').val(user?.student?.contact || user?.teacher?.contact || '');
            $('#user_degree_id').val(user?.student?.degree_id || '');
            $('#temporary-password-note').show();

            toggleUserStudentFields();

            $saveButton.text(user ? 'Update User' : 'Save User');
            $formModal.addClass('active');
        }

        function closeUserForm() {
            editingUser = null;
            $formModal.removeClass('active');
            $form[0].reset();
            clearUserFormErrors();
            toggleUserStudentFields();
        }

        function openUserDetails(user) {
            $('#detail-user-title').text(user.username || '');
            $('#detail-user-username').text(user.username || 'N/A');
            $('#detail-user-email').text(user.email || 'N/A');
            $('#detail-user-role').text(user.role_label || 'N/A');
            $('#detail-user-profile').text(user.profile_message || 'N/A');
            $('#detail-user-posts').text(user.posts_count ?? 0);
            $('#detail-user-joined').text(user.joined || 'N/A');
            $('.user-student-detail').toggle(Boolean(user.student));
            $('#detail-user-student-name').text(user.student ? `${user.student.fname || ''} ${user.student.mname || ''} ${user.student.lname || ''}`.trim() : 'N/A');
            $('#detail-user-student-contact').text(user.student?.contact || 'N/A');
            $('#detail-user-student-degree').text(user.student?.degree || 'N/A');
            $detailsModal.addClass('active');
        }

        function closeUserDetails() {
            $detailsModal.removeClass('active');
        }

        function closeUserDelete() {
            deletingUser = null;
            $deleteModal.removeClass('active');
        }

        function toggleUserStudentFields() {
            const role = $('#user_role').val();
            const isStudent = role === 'student';
            const isTeacher = role === 'teacher';
            const needsPersonFields = isStudent || isTeacher;
            const roleLabel = role ? role.charAt(0).toUpperCase() + role.slice(1) : 'User';
            const $fields = $('#user-person-fields');

            $('#user-role-info-section').toggle(role !== 'admin');
            $('#user-role-info-title').text(`${roleLabel} Information`);
            $('#user-role-info-note').hide();
            $fields.toggle(needsPersonFields);
            $fields.find('input').prop('required', needsPersonFields);
            $('#user-degree-group').toggle(isStudent);
            $('#user_degree_id').prop('required', isStudent);
        }

        function showUserAlert(message, type) {
            $alert
                .removeClass('success error')
                .addClass(type)
                .text(message)
                .show();

            setTimeout(function () {
                $alert.fadeOut(200);
            }, 3500);
        }

        function showUserFormErrors(errors) {
            Object.keys(errors).forEach(function (field) {
                $(`[data-user-error-for="${field}"]`).text(errors[field][0]);
            });
        }

        function clearUserFormErrors() {
            $('.user-form-error').text('');
        }

        function getUserFromButton(button) {
            return JSON.parse(decodeURIComponent($(button).attr('data-user')));
        }
    });
}
