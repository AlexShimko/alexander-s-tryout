<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CsvReader implement Iterator interface to have an option to iterate through file data in foreach
 * @package App\Helper
 */
class CsvReader implements \Iterator
{
    /**
     * @var false|resource
     */
    private $fileHandle;

    /**
     * @var int
     */
    private int $position = 0;

    /**
     * @var array
     */
    private array $lineData = [];

    /**
     * CsvReader constructor.
     * @param UploadedFile $uploadedFile
     */
    public function __construct(UploadedFile $uploadedFile)
    {
        $this->fileHandle = \fopen($uploadedFile->getRealPath(), 'rb');
    }

    /**
     * Close file before object destruction
     */
    public function __destruct()
    {
        if ($this->fileHandle) {
            \fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        if ($this->fileHandle) {
            $this->position = 0;
            \rewind($this->fileHandle);
        }
        $this->parseLine();
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function current(): array
    {
        return $this->lineData;
    }

    /**
     * @inheritDoc
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->position++;
        $this->parseLine();
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function valid(): bool
    {
        return $this->lineData !== [];
    }

    /**
     * Csv file lines parser
     */
    private function parseLine(): void
    {
        $this->lineData = [];
        if (!\feof($this->fileHandle)) {
            $line = \trim(\utf8_encode(\fgets($this->fileHandle)));
            $this->lineData = \str_getcsv($line);
        }
    }
}
