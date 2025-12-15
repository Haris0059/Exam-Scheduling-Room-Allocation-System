$(function () {

    // restricts inputs to numbers only
    $("#editSeatCapacity, #editCoord_x, #editCoord_y, #editCoord_z").on("input", function () {
        this.value = this.value.replace(/[^0-9.-]/g, "");
    });


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
            { 
                data: "type",
                render: function (type) {
                    if (!type) return "";
                
                    return type.toUpperCase();
                }
            },
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

    function initRoomTypeSelect2() {
    if (!$.fn.select2) {
        // if select2 not ready yet
        setTimeout(initRoomTypeSelect2, 50);
        return;
    }

    const select = $('#editType');

    if (select.hasClass('select2-hidden-accessible')) return;

    select.select2({
            dropdownParent: $('#editRoomModal'),
            width: '100%',
            placeholder: 'Select or type room type',
            tags: true,
            allowClear: true
        });
    }

    // ROOM EDIT
    
    $(document).on('shown.bs.modal', '#editRoomModal', initRoomTypeSelect2);

    $(document).on("click", ".edit-room", function () {
        const id = $(this).data("id");
        let table = $('#dataTableRooms').DataTable();

        // get row data by id
        let rowData = table.rows().data().toArray().find(r => r.id == id);
        if (!rowData) return;

        // fill modal fields
        $("#editCode").val(rowData.code);
        $("#editSeatCapacity").val(rowData.seat_capacity);
        $("#editCoord_x").val(rowData.coord_x);
        $("#editCoord_y").val(rowData.coord_y);
        $("#editCoord_z").val(rowData.coord_z);

        if (!select.find(`option[value="${rowData.type}"]`).length) {
            select.append(new Option(rowData.type, rowData.type, true, true));
        }
        select.val(rowData.type).trigger("change.select2");

        // store id for update
        $("#editRoomModal").data("id", id);
    });

    $(document).on("click", "#changeRoomSubmitBtn", function (e) {
        e.preventDefault();

        const id = $("#editRoomModal").data("id");

        const updatedRoom = {
            code: $("#editCode").val(),
            type: $("#editType").val(),
            seat_capacity: Number($("#editSeatCapacity").val()),
            coord_x: Number($("#editCoord_x").val()),
            coord_y: Number($("#editCoord_y").val()),
            coord_z: Number($("#editCoord_z").val())
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
                toastr.error(xhr.responseJSON.error, "Failed to update room");
            }
        });
    });



    // ROOM DELETE
    let roomIdToDelete = null;

    $(document).on("click", ".remove-room", function () {
        roomIdToDelete = $(this).data("id");

        // store it on modal as well (optional, but clean)
        $("#removeRoomModal").data("id", roomIdToDelete);
    });

    $(document).on("click", "#confirmRemoveRoomBtn", function () {

        const id = $("#removeRoomModal").data("id");
        if (!id) return;

        $.ajax({
            url: `http://localhost/Exam-Scheduling-Room-Allocation-System/backend/rooms/${id}`,
            method: "DELETE",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            success: function () {
                $("#removeRoomModal").modal("hide");

                $('#dataTableRooms').DataTable().ajax.reload(null, false);

                toastr.success("Room deleted successfully!", "Success");
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                toastr.error("Failed to delete room.", "Error");
            }
        });
    });

    $(document).on('hidden.bs.modal', '#removeRoomModal', function () {
        $(this).removeData("id");
        roomIdToDelete = null;
    });


    // ADD ROOM 

    $(document).on("click", "#addRoomSubmitBtn", function (e) {
        e.preventDefault();

        const newRoom = {
            code: Number($("#code").val()),
            type: $("#type").val(),
            seat_capacity: Number($("#seatCapacity").val()),
            coord_x: Number($("#coord_x").val()),
            coord_y: Number($("#coord_y").val()),
            coord_z: Number($("#coord_z").val())
        };

        // basic frontend validation
        if (!newRoom.code || !newRoom.type || !newRoom.seat_capacity) {
            toastr.error("Please fill all required fields.", "Validation Error");
            return;
        }

        $.ajax({
            url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/rooms",
            method: "POST",
            contentType: "application/json",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            data: JSON.stringify(newRoom),
            success: function () {
                $("#addRoomModal").modal("hide");

                // reset form
                $("#code, #seatCapacity, #coord_x, #coord_y, #coord_z").val("");
                $("#type").val(null).trigger("change");

                // reload table
                $('#dataTableRooms').DataTable().ajax.reload(null, false);

                toastr.success("Room created successfully!", "Success");
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                toastr.error(xhr.responseJSON?.error || "Invalid room data", "Error");
            }
        });
    });


});
