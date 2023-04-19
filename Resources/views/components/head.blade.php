@existComponent ($qnaConfig->include_head)
    <x-dynamic-component :component="$qnaConfig->include_head"/>
@endexistComponent
{!!
    Purifier::clean(
        Utils::isMobile() 
            ? $qnaConfig->mobile_content_head
            : $qnaConfig->content_head
    )
!!}