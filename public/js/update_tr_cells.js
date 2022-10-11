function update_tr_cells_fnc(args)
{
    let response = args.response;
    let fld_names = args.fld_names;
    let jsonData = JSON.parse(response);
    
    // Only highlight background color if original value changed
    for (let i = 0; i < fld_names.length; i++) {
        let class_ = jsonData['input_' + fld_names[i] + '_orig'] == jsonData['input_' + fld_names[i]] ? '' : ' updated';
        $('tr[data-id="' + jsonData.id + '"] td.' + fld_names[i]).addClass(fld_names[i] + class_).html(jsonData['input_' + fld_names[i]]);
    }
}