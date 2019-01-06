<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\SearchQueryFilter;
use App\Doctrine\Repository\SearchQueryFilterRepository;
use App\Library\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BatchAddSearchQueryFilters extends BaseCommand
{
    /**
     * @var array $entries
     */
    private $entries = [];
    /**
     * @var SearchQueryFilterRepository $searchQueryFilterRepository
     */
    private $searchQueryFilterRepository;
    /**
     * BatchAddSearchQueryFilters constructor.
     * @param SearchQueryFilterRepository $searchQueryFilterRepository
     */
    public function __construct(
        SearchQueryFilterRepository $searchQueryFilterRepository
    ) {
        parent::__construct();

        $this->searchQueryFilterRepository = $searchQueryFilterRepository;

        $this->entries = [
            [
                'reference' => 'iphone',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'samsung',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'huawei',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'zte',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'google pixel',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'xiaomi',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'oppo',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'alcatel',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
            [
                'reference' => 'lg',
                'values' => [
                    'en' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag', 'touch screen', 'protector', 'lcd', 'screen', 'shell', 'headphone', 'adapter', 'cable'],
                    'fr' => ['housse', 'masque', 'étui', 'etui', 'protecteur', 'bague', 'anneau', 'titulaire', 'détenteur', 'detenteur', 'recouvrir', 'sac à main', 'sac a main', 'écran tactile', 'ecran tactile', 'protecteur', 'lcd', 'écran', 'ecran', 'coquille', 'casque de musique', 'adaptateur', 'câble', 'cable' ,'coque'],
                    'de' => ['maske', 'hülle', 'hulle', 'schützend', 'schutzend', 'schutz', 'beschützend', 'beschutzend', 'klingelzeichen', 'fassung', 'halter', 'abdeckung', 'deckel', 'umschlag', 'geldborse', 'geldbeutel', 'handtasche', 'Berührungsempfindlicher Bildschirm', 'beruhrungsempfindlicher bildschirm', 'schutz', 'beschützer', 'beschutzer', 'bildschirm', 'schirm', 'schale', 'hülle', 'hulle', 'Kopfhorer', 'Kopfhorer', 'adapter', 'kabel'],
                    'es' => ['máscara', 'mascara', 'mascarilla', 'careta', 'caja', 'protector', 'anillo', 'aro', 'soporte', 'cubrir', 'tapar', 'encubrir', 'bolso', 'pantalla táctil', 'pantalla tactil', 'lcd', 'pantalla', 'cáscara', 'cascara', 'funda', 'carcasa', 'auricular', 'adaptador'],
                    'it' => ['custodia', 'maschera', 'cassa', 'protettivo', 'ring', 'supporto', 'ricoprire', 'borsa', 'borsetta', 'protettore', 'lcd', 'schermo', 'paravento', 'guscio', 'cuffie', 'adattatore', 'cavo'],
                    'pl' => ['maska', 'etui', 'skrzynka', 'ochronny', 'zabezpieczający', 'opiekuńczy', 'opiekunczy', 'pierścionek', 'pierscionek', 'uchwyt', 'podstawka', 'pokrywa', 'osłona', 'oslona', 'portmonetka', 'kiesa', 'torba', 'torebka', 'ekran dotykowy', 'protektor', 'lcd', 'ekran', 'powłoka', 'powloka', 'słuchawki', 'sluchawki', 'adapter', 'łącznik', 'lącznik', 'kabel'],
                ],
            ],
        ];
    }
    /**
     * @void
     */
    public function configure()
    {
        $this->setName('app:batch_add_search_query_filters');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->output->writeln(sprintf(
            '<info>Starting command...</info>'
        ));

        $entries = Util::createGenerator($this->entries);
        foreach ($entries as $entry) {
            $item = $entry['item'];

            /** @var SearchQueryFilter $existingSearchQueryFilter */
            $existingSearchQueryFilter = $this->searchQueryFilterRepository->findOneBy([
                'reference' => $item['reference'],
            ]);

            if (!empty($existingSearchQueryFilter)) {
                $existingSearchQueryFilter->setValues(jsonEncodeWithFix($item['values']));

                $this->searchQueryFilterRepository->persistAndFlush($existingSearchQueryFilter);

                continue;
            }

            $searchQueryFilter = new SearchQueryFilter(
                $item['reference'],
                jsonEncodeWithFix($item['values'])
            );

            $this->searchQueryFilterRepository->persistAndFlush($searchQueryFilter);
        }

        $output->writeln('');
        $output->writeln(sprintf(
            '<info>Successfully executed command</info>'
        ));
    }
}