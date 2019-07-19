<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/manifest.js"></script>
    <script src="/js/vendor.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
<input type="hidden" id="notices" name="notices" value="{{$notices}}">
<input type="hidden" id="products" name="products" value="{{$products}}">
<form action="{{route('store.search')}}">
<input type="text" name="store_name" id="store_name">
<button type="submit">검색</button>
</form>
<br>
<br>

<table class="table table-bordered">
    <tr>
        <th>매장명</th>
        <th>업종</th>
        <th>전화번호</th>
    </tr>
@foreach ($items as $item)
    <tr>
        <td><a href="#" onclick="select_store({{$item}}); return false;">{{$item->name}}</a></td>
        <td>{{$item->category}}</td>
        <td>{{$item->phone}}</td>
    </tr>
@endforeach
</table>
</body>

<script>
function select_store(obj) {
    opener.store.value = obj.name;
    opener.store_id.value = obj.id;
    var notices = JSON.parse($('#notices').val());
    var products = JSON.parse($('#products').val());
    
    if(notices != null) {
        $(opener.document).find('#notice_list').empty();
        $(opener.document).find('#notice_list').append(`
                    <tr>
                        <th>제목</th>
                        <th>내용</th>
                        <th>등록일</th>
                    </tr>
                    `);
        notices.forEach(notice => {
            if(notice['store_id'] == obj.id) {
                $(opener.document).find('#notice_list').append(`
                    <tr>
                        <td>` + notice['title'] + `</td>
                        <td>` + notice['content'] + `</td>
                        <td>` + notice['updated_at'] + `</td>
                    </tr>`);    
            }
        });
    }
    if(products != null) {
        $(opener.document).find('#rs-kind-list').empty();
        products.forEach(product => {
            if(product['store_id'] == obj.id) {
                $(`<input type="radio" class="kind" name="kind" id="kind_` + product['eng_name'] + `" value="` + product['name'] + `" title="` + product['exp'] + `" price="` + product['price'] + `">
                <label for="kind_` + product['eng_name'] + `" title="` + product['exp'] + `">` + product['name'] + `</label>`)
                .appendTo($(opener.document).find('#rs-kind-list'));
            }
        });
        
    }
    self.close();
}
</script>

