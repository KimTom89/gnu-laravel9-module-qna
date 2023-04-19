{!!
    Purifier::clean(
        Utils::isMobile() 
            ? $qnaConfig->mobile_content_tail
            : $qnaConfig->content_tail
    )
!!}
@existComponent ($qnaConfig->include_tail)
    <x-dynamic-component :component="$qnaConfig->include_tail"/>
@endexistComponent