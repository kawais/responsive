//phpdocumentor/src/phpdocumentor/plugin/core/transformer/writer/sourcecode.php
<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @copyright 2010-2014 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Plugin\Core\Transformer\Writer;

use phpDocumentor\Descriptor\FileDescriptor;
use phpDocumentor\Descriptor\ProjectDescriptor;
use phpDocumentor\Transformer\Transformation;
use phpDocumentor\Transformer\Writer\WriterAbstract;

/**
 * Sourcecode transformation writer; generates syntax highlighted source files in a destination's subfolder.
 */
class Sourcecode extends WriterAbstract
{
    /**
     * This method writes every source code entry in the structure file to a highlighted file.
     *
     * @param ProjectDescriptor $project        Document containing the structure.
     * @param Transformation    $transformation Transformation to execute.
     *
     * @return void
     */
    public function transform(ProjectDescriptor $project, Transformation $transformation)
    {
        $artifact = $transformation->getTransformer()->getTarget()
            . DIRECTORY_SEPARATOR
            . ($transformation->getArtifact()
                ? $transformation->getArtifact()
                : 'source');

        /** @var FileDescriptor $file */
        foreach ($project->getFiles() as $file) {
            $filename = $file->getPath();
            $source   = $file->getSource();

            $root = str_repeat('../', count(explode(DIRECTORY_SEPARATOR, $filename)));
            $path = $artifact . DIRECTORY_SEPARATOR . $filename;
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            $source = htmlentities($source);
            file_put_contents(
                $path.'.html',
                <<<HTML
        <script>
        if (typeof jQuery == 'undefined') {
            document.write("<script src='http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js'><\/script>");
        }
        if (typeof Prism == 'undefined') {
            // document.write("<script src='{$root}prism/prism.js'><\/script>");
            // document.write("<link href='{$root}prism/prism.css' rel='stylesheet' type='text/css'><\/script>");
        }
        </script>
        <script
            type="text/javascript"
            src="{$root}prism/prism.js">
        </script>
        <link
            href="{$root}prism/prism.css" rel="stylesheet"
            type="text/css"
        />


<div class="modal">
        <pre class="brush: php line-numbers language-php"><code class="">$source</code></pre>
</div>
        <script type="text/javascript">
             jQuery('.gutter div').each(function(key, data){
                jQuery(data).prepend('<a name="L'+jQuery(data).text()+'"/>');
             });
        </script>


HTML
            );

        }
    }
}
