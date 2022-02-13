<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SB\PDFMerger\PDFMerger;
use setasign\Fpdi\Fpdi;

final class MergePdfTest extends TestCase
{
    public function testMergePdf()
    {
        $pdf = new PDFMerger;
        $pdf1 = __DIR__ . '/../resources/pdf1.pdf';
        $pdf2 = __DIR__ . '/../resources/pdf2.pdf';
        $merged = __DIR__ . '/../resources/merged.pdf';

        $pdf->addPDF($pdf1, 'all');
        $pdf->addPDF($pdf2, 'all');

        try {
            $isSuccess = $pdf->merge('file', $merged, 'P');

            $this->assertEquals($isSuccess, true);
            $this->assertEquals(\is_readable($merged), true);
            $this->assertNotEmpty(\md5(\file_get_contents($merged)));

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($merged);

            $this->assertEquals(2, $pageCount);
        } finally {
            // Delete temp merged file if exists even test fails.
            try {
                \unlink($merged);
            } catch (\Throwable $e) {
            }
        }
    }
}
