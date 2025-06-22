<?php

namespace App\Service;

use App\Entity\Specialty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class SpecialtySyncService
{
    public function __construct(
        private HttpClientInterface    $httpClient,
        private EntityManagerInterface $em,
    )
    {
    }

    public function sync(string $specialtyJsonUrl): int
    {
        $response = $this->httpClient->request('GET', $specialtyJsonUrl);
        $content = $response->getContent();
        $specialtiesData = json_decode($content, true);

        $importedCount = 0;

        foreach ($specialtiesData as $data) {
            $specialty = $this->em->getRepository(Specialty::class)->find($data['id']) ?? new Specialty();

            $specialty->setExternalId($data['id']);
            $specialty->setName($data['name']);
            $specialty->setTitle($data['title']);
            $specialty->setUpdateAt((new \DateTimeImmutable())->setTimestamp($data['update_at']));

            if (isset($data['description'])) {
                $specialty->setDescription($data['description']);
            }

            $this->em->persist($specialty);

            $importedCount++;

        }

        $this->em->flush();
        return $importedCount;
    }
}
