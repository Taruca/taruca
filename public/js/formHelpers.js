$.fn.getFormVal = function () {
    var data = $("form").serializeArray();
    var obj = {};
    $.each(data, function (k, v) {
        obj[v.name] = v.value;
    });
    return obj;
};

$.fn.setFormVal = function (jsonValue) {
    var obj = this;
    $.each(jsonValue, function (name, ival) {
        var $oinput = obj.find("input[name=" + name + "]");
        if ($oinput.attr("type") == "checkbox") {
            if (ival !== null) {
                var checkboxObj = $("[name=" + name + "]");
                istr = ival.toString();
                var checkArray = istr.split(";");
                for (var i = 0; i < checkboxObj.length; i++) {
                    for (var j = 0; j < checkArray.length; j++) {
                        if (checkboxObj[i].value == checkArray[j]) {
                            checkboxObj[i].click();
                        }
                    }
                }
            }
        }
        else if ($oinput.attr("type") == "radio") {
            $oinput.each(function () {
                var radioObj = $("[name=" + name + "]");
                for (var i = 0; i < radioObj.length; i++) {
                    if (radioObj[i].value == ival) {
                        radioObj[i].click();
                    }
                }
            });
        }
        else if ($oinput.attr("type") == "textarea") {
            obj.find("[name=" + name + "]").html(ival);
        }
        else {
            obj.find("[name=" + name + "]").val(ival);
        }
    })
};

//idArray中的id格式为'操作-字段名'
//获取modal中input的值
function getModalVal(idArray) {
    var data = {};
    $.each(idArray, function (index, value) {
        var key = value.split('-')[1];
        data[key] = $("#" + value).val();
    });
    return data;
}

//获取同一个class的标签的值
function getClassGroupVal(className) {
    var data = [];
    $(className).each(function () {
        data.push($.trim($(this).text()));
    });
    return data;
}

function clearForm(form) {
    // input清空
    $(':input', form).each(function () {
        var type = this.type;
        var tag = this.tagName.toLowerCase(); // normalize case
        if (type == 'text' || type == 'password' || tag == 'textarea')
            this.value = "";
        // 多选checkboxes清空
        // select 下拉框清空
        else if (tag == 'select') {
            this.selectedIndex = 0;
        }
    });
}