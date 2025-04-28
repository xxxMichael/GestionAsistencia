<?php

interface IReportService {
    public function generate(array $params = []): void;
}
