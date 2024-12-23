const userTable = $('#userTable').DataTable({
    serverSide: true,
    processing: true,
    order: [[0, 'desc']],
    ajax: {
        url: 'api_user.php',
        type: 'POST',
        data: function (d) {
            return {
                action: 'list',
                draw: d.draw,
                start: d.start,
                length: d.length,
                searchValue: d.search.value,
                orderColumn: d.columns[d.order[0].column].data,
                orderDirection: d.order[0].dir
            };
        },
        error: function (xhr, status, error) {
            console.error('Error with DataTables AJAX request:', error);
            console.log('Response:', xhr.responseText);
        }
    },
    lengthMenu: [5, 10, 15, 30],
    pageLength: 10,

    columns: [
        {data: 'id', render: $.fn.dataTable.render.text()},
        {data: 'username', render: $.fn.dataTable.render.text()},
        {data: 'email', render: $.fn.dataTable.render.text()},
        {data: 'full_name', render: $.fn.dataTable.render.text()},
        {
            data: 'type_name',
            render: function (data) {
                let roleClass = '';
                let roleLabel = '';

                switch (data) {
                    case 'VIP':
                        roleClass = 'bg-danger';
                        roleLabel = 'Khách hàng VIP';
                        break;
                    default:
                        roleClass = 'bg-secondary';
                        roleLabel = 'Khách hàng thường';
                }
                return `<span class="badge ${roleClass}">${$.fn.dataTable.render.text().display(roleLabel)}</span>`;
            }
        },
        {
            data: 'is_active',
            render: function (data) {
                return `<span class="badge ${data == 1 ? 'bg-success' : 'bg-danger'}">
                        ${$.fn.dataTable.render.text().display(data == 1 ? 'Kích hoạt' : 'Vô hiệu hóa')}
                    </span>`;
            }
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data) {
                const id = $.fn.dataTable.render.text().display(data.id);
                return `
                    <div class="action-buttons">
                        <a href="customer-detail.php?id=${id}" class="btn btn-primary btn-sm">Chi tiết</a>
                        <a href="edit_customer.php?id=${id}" class="btn btn-warning btn-sm">Sửa</a>
                        <button class="btn btn-warning btn-sm disable-btn" data-id="${id}">
                            ${data.is_active == 1 ? 'Vô hiệu hóa' : 'Kích hoạt'}
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${id}">Xóa</button>
                    </div>
                `;

            }
        }
    ],
    language: {
        processing: 'Đang xử lý...',
        emptyTable: 'Không có dữ liệu.',
        paginate: {
            first: 'Đầu',
            last: 'Cuối',
            next: 'Tiếp',
            previous: 'Trước'
        }
    }
});


$('#userTable').on('click', '.delete-btn', function () {
    const userId = $(this).data('id');
    if (confirm('Bạn có chắc muốn xóa người dùng này?')) {
        $.post('api_user.php', {action: 'delete', id: userId}, function (response) {
            if (response.error) {
                alert(response.error);
                return;
            }
            userTable.ajax.reload();
        }).fail(function () {
            alert('Không thể thực hiện thao tác xóa. Vui lòng thử lại.');
        });
    }
});

$('#userTable').on('click', '.disable-btn', function () {
    const userId = $(this).data('id');
    const action = $(this).text().trim() === 'Vô hiệu hóa' ? 'disable' : 'enable';
    $.post('api_user.php', {action, id: userId}, function (response) {
        if (response.error) {
            alert(response.error);
            return;
        }
        userTable.ajax.reload();
    }).fail(function () {
        alert('Không thể thực hiện thao tác. Vui lòng thử lại.');
    });
});
