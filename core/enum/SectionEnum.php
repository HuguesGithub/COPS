<?php
namespace core\enum;

/**
 * @since v1.23.07.02
 */
enum SectionEnum: string
{
    case Alpha = 'alpha';
    case Beta = 'beta';
    case Gamma = 'gamma';
    case Delta = 'delta';
    case Epsilon = 'epsilon';
    case Theta = 'theta';
    case Mu = 'mu';
    case Pi = 'pi';
    case Sigma = 'sigma';
    case Tau = 'tau';

    public function label(): string
    {
        return match($this) {
            static::Alpha   => 'A-Alpha',
            static::Beta    => 'A-Beta',
            static::Gamma   => 'A-Gamma',
            static::Delta   => 'B-Delta',
            static::Epsilon => 'B-Epsilon',
            static::Theta   => 'B-Theta',
            static::Mu      => 'B-Mu',
            static::Pi      => 'C-Pi',
            static::Sigma   => 'C-Sigma',
            static::Tau     => 'C-Tau',
            default         => '%',
        };
    }
}
