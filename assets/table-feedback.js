$(document).ready(function() {
    const feedbackTable = $('#feedbackTable').DataTable({
        serverSide: true,
        processing: true,
        searching: false,
        ajax: {
            url: 'feedback.php',
            type: 'POST',
            data: function(d) {
                return {
                    action: 'list',
                    draw: d.draw,
                    start: d.start,
                    length: d.length
                };
            }
        },
        lengthMenu: [5, 10, 15, 30],
        pageLength: 10,
        columns: [
            {data: 'name'},
            {data: 'phone'},
            {data: 'email'},
            {
                data: null,
                orderable: false,
                searchable: true,
                render: function(data) {
                    return `
                            <button class="btn btn-info btn-sm view-btn" data-id="${data.id}">Xem chi tiết</button>
                            <button class="btn btn-warning btn-sm mark-read-btn" data-id="${data.id}" data-is-read="${data.is_read}">${data.is_read == 1 ? 'Đã đọc' : 'Đánh dấu là đã đọc'}</button>
                        `;
                }
            }
        ],
        language: {
            processing: 'Đang xử lý...',
            emptyTable: 'Không có dữ liệu.'
        }
    });

    $(document).on('click', '.view-btn', function() {
        const feedbackId = $(this).data('id');
        $.ajax({
            url: 'feedback.php',
            method: 'POST',
            data: {action: 'view', id: feedbackId},
            success: function(response) {
                const feedback = JSON.parse(response);
                $('#feedbackName').text(feedback.name);
                $('#feedbackEmail').text(feedback.email);
                $('#feedbackPhone').text(feedback.phone);
                $('#feedbackAddress').text(feedback.address);
                $('#feedbackMessage').text(feedback.message);
                $('#feedbackDate').text(feedback.created_at);
                $('#viewFeedbackModal').modal('show');
                feedbackTable.ajax.reload();
            }
        });

    });

    $(document).on('click', '.mark-read-btn', function() {
        const feedbackId = $(this).data('id');
        const isRead = $(this).data('is-read');
        markAsRead(feedbackId,isRead)
    });
});


function markAsRead(feedbackId, isRead) {
    if (isRead == 0) {
        $.ajax({
            url: 'feedback.php',
            method: 'POST',
            data: {action: 'markAsRead', id: feedbackId},
            success: function(response) {
                if (response == 'success') {
                    $(this).text('Đã đọc').data('is-read', 1);
                    feedbackTable.ajax.reload();
                }
            }
        });
    }
}