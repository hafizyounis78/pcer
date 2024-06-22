<ul class="page-breadcrumb breadcrumb">
    <li>
        <a href="{{'patient/search'}}">Home</a>
        <i class="fa fa-circle"></i>
    </li>
    @if(isset($location_link))
        <li>
            <a href="{{url($location_link)}}">{{$location_title}}</a>
            @if(isset($page_title))
                <i class="fa fa-angle-right"></i>
            @endif
        </li>
    @endif
    @if(isset($page_title))
        <li>
            <span class="active">{{$page_title}}</span>
        </li>
    @endif
</ul>