Loader
    Preloadable
        Repository
            Reusable
            Iterator
        Embedded
            ArrayAccess
            

Embedded\Preloader
    Loader<-Preloadable


=======================

SourceInterface
    ->loader()
    ->preloadingProxy()
    ReusableResult
        ->reusableResult()

Preloader implements Source
    ->loader()
    ->preloadingProxy()
    ->resultStep()


    