<?php
if(!function_exists('echo_active'))
{
    function echo_active($condition)
    {
        return $condition?'active':'';
    }
}
if(!function_exists('echo_selected'))
{
    function echo_selected($condition)
    {
        return $condition?'selected':'';
    }
}
if(!function_exists('store_upload'))
{
    /**
     * Move an uploaded document under public/images/<folder> and return the
     * web-relative path to store on the record, or null when nothing was sent.
     */
    function store_upload($file, string $folder): ?string
    {
        if (empty($file) || !is_object($file)) {
            return null;
        }

        $ext = strtolower($file->getClientOriginalExtension());
        // uniqid() rather than time(): two documents saved in the same second
        // would otherwise resolve to the same filename and overwrite.
        $name = sha1(uniqid('', true)) . '.' . $ext;
        $relative = 'images/' . trim($folder, '/') . '/' . $name;

        $file->move(public_path('images/' . trim($folder, '/')), $name);

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'webp'])) {
            try {
                $image = \Nette\Utils\Image::fromFile(public_path($relative));
                $image->save(public_path($relative), 20);
            } catch (\Throwable $e) {
                // A file that carries an image extension but will not decode is
                // kept as uploaded; compression is not worth failing the save.
                \Illuminate\Support\Facades\Log::warning(
                    'Could not compress upload ' . $relative . ': ' . $e->getMessage()
                );
            }
        }

        return $relative;
    }
}

