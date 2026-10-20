
function system() {
    return window.matchMedia("(prefers-color-scheme: light)").matches ? "light" : "dark"
}

window.theme = localStorage.getItem("theme") ?? system()
window.font = localStorage.getItem("font") ?? 16
function setting() {
    const path = location.pathname.split("/").pop()
    if ($(`a.link[href='${path}']`).length > 0) {
        $(`a.link[href='${path}']`).addClass("active")
    } else if(path == "admin.html") {
        $(`a.link[href='login.html']`).addClass("active")
    } else {
        $(`a.link[href='index.html']`).addClass("active")
    }
    $("#theme").val(window.theme)
    if (window.theme == "system") {
        $("html").attr("data-bs-theme",system())
    } else {
        $("html").attr("data-bs-theme",window.theme)
    }
    $("#font").val(window.font)
    $("html").css("font-size",window.font + "px")
}

setting()

$("#theme").on("change",function () {
    localStorage.setItem("theme",$(this).val())
    location.reload()
})


$("#font").on("change",function () {
    localStorage.setItem("font",$(this).val())
    location.reload()
})

function empty() {
    return `<span class="bg-secondary rounded-1 p-1">無資料</span>`
}

function cut(val) {
    const limit = 30
    if (val.length > limit) {
        return `${val.slice(0,limit)}<span class="readmore d-none">${val.slice(limit)}</span><a class="readtoggle">...閱讀更多</a>`
    } else {
        return val
    }
}

function level(row) {
    if (row['crowd'] <= 40 && row['parking'] >= 4 && row['average'] <= 200) {
        return `<span class="bg-success rounded-1 p-1" style="color: #fff">高</span>`
    } else if (row['crowd'] <= 70 && row['parking'] >= 3) {
        return `<span class="bg-warning rounded-1 p-1 text-dark">中</span>`
    } else {
        return `<span class="bg-danger rounded-1 p-1" style="color: #fff">低</span>`
    }
}

$(document).on("click",".readtoggle",function () {
    $(this).prev().toggleClass("d-none")
    $(this).text(
        $(this).text() == "閱讀更少" ? "...閱讀更多" : "閱讀更少"
    )
})