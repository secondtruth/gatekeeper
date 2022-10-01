<?php
/*
 * Gatekeeper
 * Copyright (C) 2022 Christian Neff
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 */

use PhpCsFixer\Fixer\Alias\BacktickToShellExecFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\CastNotation\ShortScalarCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\NoPhp4ConstructorFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfStaticAccessorFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Comment\NoTrailingWhitespaceInCommentFixer;
use PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\CombineNestedDirnameFixer;
use PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoBlankLinesAfterPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAddMissingParamAnnotationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocInlineTagNormalizerFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoUselessInheritdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocReturnSelfReferenceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocScalarFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTagCasingFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->sets([SetList::PSR_12]);

    $year = date('Y');
    $header = <<<EOF
        Gatekeeper
        Copyright (C) {$year} Christian Neff
        
        Permission to use, copy, modify, and/or distribute this software for
        any purpose with or without fee is hereby granted, provided that the
        above copyright notice and this permission notice appear in all copies.
        EOF;

    $config->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short'
    ]);
    $config->rule(BacktickToShellExecFixer::class);
    $config->ruleWithConfiguration(ClassAttributesSeparationFixer::class, [
        'elements' => [
            'method' => 'one'
        ]
    ]);
    $config->rule(CombineNestedDirnameFixer::class);
    $config->rule(DirConstantFixer::class);
    $config->ruleWithConfiguration(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => ['uses', 'package', 'subpackage']
    ]);
    $config->ruleWithConfiguration(HeaderCommentFixer::class, [
        'header' => $header,
        'location' => 'after_open',
        'separate' => 'bottom'
    ]);
    $config->rule(IndentationTypeFixer::class);
    $config->rule(ModernizeTypesCastingFixer::class);
    $config->rule(NativeFunctionCasingFixer::class);
    $config->rule(NewWithBracesFixer::class);
    $config->rule(NoBlankLinesAfterPhpdocFixer::class);
    $config->rule(NoEmptyPhpdocFixer::class);
    $config->rule(NoEmptyStatementFixer::class);
    $config->rule(NoLeadingImportSlashFixer::class);
    $config->rule(NoPhp4ConstructorFixer::class);
    $config->rule(NoShortBoolCastFixer::class);
    $config->rule(NoSpacesAroundOffsetFixer::class);
    $config->rule(NoTrailingWhitespaceInCommentFixer::class);
    $config->rule(NoUnusedImportsFixer::class);
    $config->rule(NoUselessReturnFixer::class);
    $config->rule(NoWhitespaceBeforeCommaInArrayFixer::class);
    $config->rule(NullableTypeDeclarationForDefaultNullValueFixer::class);
    $config->ruleWithConfiguration(PhpdocAddMissingParamAnnotationFixer::class, [
        'only_untyped' => false
    ]);
    $config->rule(PhpdocAlignFixer::class);
    $config->rule(PhpdocIndentFixer::class);
    $config->rule(PhpdocInlineTagNormalizerFixer::class);
    $config->rule(PhpdocLineSpanFixer::class);
    $config->rule(PhpdocNoAliasTagFixer::class);
    $config->rule(PhpdocNoEmptyReturnFixer::class);
    $config->rule(PhpdocNoUselessInheritdocFixer::class);
    $config->rule(PhpdocReturnSelfReferenceFixer::class);
    $config->rule(PhpdocScalarFixer::class);
    $config->rule(PhpdocSeparationFixer::class);
    $config->rule(PhpdocSummaryFixer::class);
    $config->ruleWithConfiguration(PhpdocTagCasingFixer::class, [
        'tags' => ['inheritdoc']
    ]);
    $config->rule(PhpdocTrimFixer::class);
    $config->rule(SelfStaticAccessorFixer::class);
    $config->rule(ShortScalarCastFixer::class);
    $config->rule(SingleLineCommentStyleFixer::class);
    $config->rule(SingleQuoteFixer::class);
    $config->rule(SwitchCaseSemicolonToColonFixer::class);
    $config->rule(TernaryOperatorSpacesFixer::class);
    $config->rule(TernaryToNullCoalescingFixer::class);
    $config->rule(TrimArraySpacesFixer::class);
    $config->rule(WhitespaceAfterCommaInArrayFixer::class);
    $config->ruleWithConfiguration(YodaStyleFixer::class, [
       'equal' => false,
       'identical' => false,
       'less_and_greater' => false,
    ]);
    $config->ruleWithConfiguration(OrderedImportsFixer::class, [
        'sort_algorithm' => OrderedImportsFixer::SORT_NONE
    ]);

    $config->indentation('    ');
    $config->lineEnding("\n");

    $config->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    $config->skip([__DIR__ . '/vendor/', __DIR__ . '/rector.php']);
};
