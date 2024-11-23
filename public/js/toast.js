function showToast(message, type) {
    let toast = {
        message: "",
        className: "",
        type: "",
        background: "",
    };
    if (type != "" && message != "") {
        toast.message = message;
        if (type === "success") {
            toast.className = "fas fa-check-circle text-light";
            toast.type = type;
            toast.background = "#3dc763";
        } else if (type === "danger" || type === "error") {
            toast.className = "fas fa-xmark-circle text-light";
            toast.type = type;
            toast.background = "#ed3d3d";
        } else if (type === "warning") {
            toast.className = "fas fa-xmark-circle text-light";
            toast.type = type;
            toast.background = "#f1c40f";
        } else if (type === "info") {
            toast.className = "fas fa-info-circle text-light";
            toast.type = type;
            toast.background = "#3498db";
        }
    }
    const notyf = new Notyf({
        types: [
            {
                duration: 2000,
                type: toast.type,
                background: toast.background,
                icon: {
                    className: toast.className,
                    tagName: "i",
                    text: "",
                },
            },
        ],
    });

    notyf.open({
        type: toast.type,
        message: toast.message,
        dismissible: true,
    });
}

function showToast(message, type, duration) {
    let toast = {
        message: "",
        className: "",
        type: "",
        background: "",
    };
    if (type != "" && message != "") {
        toast.message = message;
        if (type === "success") {
            toast.className = "fas fa-check-circle text-light";
            toast.type = type;
            toast.background = "#3dc763";
        } else if (type === "danger") {
            toast.className = "fas fa-xmark-circle text-light";
            toast.type = type;
            toast.background = "#ed3d3d";
        } else if (type === "warning") {
            toast.className = "fas fa-xmark-circle text-light";
            toast.type = type;
            toast.background = "#f1c40f";
        } else if (type === "info") {
            toast.className = "fas fa-info-circle text-light";
            toast.type = type;
            toast.background = "#3498db";
        }
    }
    const notyf = new Notyf({
        types: [
            {
                duration: duration,
                type: toast.type,
                background: toast.background,
                icon: {
                    className: toast.className,
                    tagName: "i",
                    text: "",
                },
            },
        ],
    });

    notyf.open({
        type: toast.type,
        message: toast.message,
        dismissible: true,
    });
}
