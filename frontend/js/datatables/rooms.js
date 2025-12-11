$(function () {

    let table = $('#dataTableRooms').DataTable({
        ajax: {
            url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/rooms",
            type: "GET",
            dataSrc: "data",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            }
        },
        columns: [
            { data: "code" },
            { data: "type" },
            { data: "seat_capacity" },
            {
                data: "id",
                className: "actions-column",
                orderable: false,
                searchable: false,
                render: function(id) {
                    return `
                        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm edit-room"
                           data-id="${id}"
                           data-toggle="modal"
                           data-target="#editRoomModal">
                            <i class="fas fa-edit fa-sm text-white-50"></i> Edit
                        </a>
                        <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm remove-room"
                           data-id="${id}"
                           data-toggle="modal"
                           data-target="#removeRoomModal">
                            <i class="fas fa-times fa-sm text-white-50"></i> Remove
                        </a>
                    `;
                }
            }
        ]
    });

    $(document).on("click", ".edit-room", function () {
        const id = $(this).data("id");

        let table = $('#dataTableRooms').DataTable();

        // get row data by id
        let rowData = table.rows().data().toArray().find(r => r.id == id);

        if (!rowData) return;

        // fill modal fields
        $("#code").val(rowData.code);
        $("#type").val(rowData.type);
        $("#seatCapacity").val(rowData.seat_capacity);
        $("#coord_x").val(rowData.coord_x);
        $("#coord_y").val(rowData.coord_y);
        $("#coord_z").val(rowData.coord_z);

        // store id for update
        $("#editRoomModal").data("id", id);
    });

    $(document).on("click", "#changeRoomSubmitBtn", function (e) {
        e.preventDefault();

        const id = $("#editRoomModal").data("id");

        const updatedRoom = {
            code: $("#code").val(),
            type: $("#type").val(),
            seat_capacity: parseInt($("#seatCapacity").val()),
            coord_x: parseFloat($("#coord_x").val()),
            coord_y: parseFloat($("#coord_y").val()),
            coord_z: parseFloat($("#coord_z").val())
        };

        $.ajax({
            url: `http://localhost/Exam-Scheduling-Room-Allocation-System/backend/rooms/${id}`,
            method: "PUT",
            contentType: "application/json",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            data: JSON.stringify(updatedRoom),
            success: function () {
                $("#editRoomModal").modal("hide");
                $('#dataTableRooms').DataTable().ajax.reload(null, false);

                toastr.success("Room updated successfully!", "Success");
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                toastr.error("Failed to update room. Please try again.", "Error");
            }
        });
    });

    $(document).on("click", ".remove-room", function () {
        const roomId = $(this).data("id");
        console.log("Remove clicked:", roomId);
        // confirm delete in modal...
    });

});
