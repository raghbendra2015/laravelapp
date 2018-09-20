<?php

use Illuminate\Http\Request;

class Helpers {

    /**
     * Send email
     * @param $template
     * @param $data
     * @param $attributes
     * @return array
     */
    public static function email($template, $data, $attributes) {
        \Mail::send($template, $data, function($message) use($attributes) {
            $message->from("raghbendra.nayak@systematixindia.com", "SIPL");
            $message->to($attributes['email_to'], $attributes['name_to'])->subject($attributes['subject']);
        });

        return Mail::failures();
    }

    /*
     * Method to strip tags globally.
    */
    public static function globalXssClean(Request $request)
    {
        // Recursive cleaning for array [] inputs, not just strings.
        $sanitized = static::arrayStripTags($request->all());
        $request->merge($sanitized);
    }

    /**
     * Method to strip tags
     *
     * @param $array
     * @return array
     */
    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
            $key = strip_tags($key);

            // If the value is an array, we will just recurse back into the
            // function to keep stripping the tags out of the array,
            // otherwise we will set the stripped value.
            if (is_array($value)) {
                $result[$key] = static::arrayStripTags($value);
            } else {
                // I am using strip_tags(), you may use htmlentities(),
                // also I am doing trim() here, you may remove it, if you wish.
                $result[$key] = trim(strip_tags($value));
            }
        }

        return $result;
    }
}
